import Shepherd from 'shepherd.js';

async function loadTourConfig(route) {
    try {
        const response = await fetch(`/tour-guide/get-steps-for-route?route=${route}`);
        if (response.ok) {
            const data = await response.json();
            if (data.steps && data.steps.length > 0) {
                return {
                    route: data.route,
                    version: data.version,
                    steps: data.steps,
                    defaultStepOptions: {}
                };
            }
        }
    } catch (e) {
        console.error(`Error loading tour config for route "${route}":`, e);
        return null;
    }
}

// Function to create and add pause button to the tour footer
function createPauseButton(tour, route, version) {
    // Add styles to the document
    if (!document.getElementById('tour-pause-button-styles')) {
        const styleEl = document.createElement('style');
        styleEl.id = 'tour-pause-button-styles';
        document.head.appendChild(styleEl);
    }

    // Function to add pause button to the current step's footer
    const addPauseButtonToFooter = () => {
        // Use setTimeout to ensure the DOM has been updated
        setTimeout(() => {
            // Find all shepherd footers
            const footers = document.querySelectorAll('.shepherd-footer');

            // Add pause button to the most recent footer (current step)
            if (footers.length > 0) {
                const currentFooter = footers[footers.length - 1];

                // Check if the footer already has a pause button
                if (!currentFooter.querySelector('.tour-pause-button')) {
                    // Create pause button element
                    const pauseButton = document.createElement('button');
                    pauseButton.className = 'tour-pause-button';
                    pauseButton.textContent = 'Pause';
                    pauseButton.title = 'Pause Tour';

                    // Add click event to pause the tour
                    pauseButton.addEventListener('click', () => {
                        const currentStepIndex = tour.getCurrentStep().id;
                        // Save the current step index to localStorage
                        localStorage.setItem(`tourPaused_${route}_v${version}`, currentStepIndex);
                        // Hide the tour
                        tour.hide();
                    });

                    // Add the button to the footer (at the beginning)
                    currentFooter.insertBefore(pauseButton, currentFooter.firstChild);
                }
            }
        }, 50); // Small delay to ensure the DOM has been updated
    };

    // Add pause button to each step when it's shown
    tour.on('show', addPauseButtonToFooter);

    return addPauseButtonToFooter;
}

export async function initTourGuide(input = 'default') {
    let config;

    if (typeof input === 'string') {
        config = await loadTourConfig(input);
    } else {
        config = input;
    }

    if (!config) {
        console.warn('No tour config available.');
        return;
    }

    const {
        steps = [],
        defaultStepOptions = {},
        route = '/',
        version = 1
    } = config;

    const tour = new Shepherd.Tour({
        useModalOverlay: true,
        classPrefix: 'pyz-',
        useHistory: true,
        defaultStepOptions: {
            cancelIcon: {enabled: true},
            classes: 'shepherd-theme-default',
            scrollTo: true,
            ...defaultStepOptions
        }
    });

    steps.forEach((step, index) => {
        const isFirstStep = index === 0;
        const isLastStep = index === steps.length - 1;

        // Generate buttons based on step position
        const buttons = [];

        // Add back button if not the first step
        if (!isFirstStep) {
            buttons.push({
                text: 'Back',
                classes: 'shepherd-button-secondary',
                action: tour.back
            });
        }

        // Add next button if not the last step, otherwise add finish button
        if (!isLastStep) {
            buttons.push({
                text: 'Next',
                classes: 'shepherd-button-primary',
                action: tour.next
            });
        } else {
            buttons.push({
                text: 'Finish',
                classes: 'shepherd-button-primary',
                action: tour.complete
            });
        }

        tour.addStep({
            ...step,
            buttons: buttons // Override any buttons defined in the backoffice module
        });
    });

    const storageKey = `tourCompleted_${route}_v${version}`;

    tour.on('complete', () => {
        localStorage.setItem(storageKey, 'true');
        // Also remove any paused state when tour is completed
        localStorage.removeItem(`tourPaused_${route}_v${version}`);
    });

    tour.on('cancel', () => {
        localStorage.setItem(storageKey, 'true');
        // Also remove any paused state when tour is cancelled
        localStorage.removeItem(`tourPaused_${route}_v${version}`);
    });

    // Add event listener for tour start to create the pause button
    tour.on('start', () => {
        createPauseButton(tour, route, version);
    });

    window.shepherdTour = tour;

    // Check if there's a paused tour
    const pausedStepId = localStorage.getItem(`tourPaused_${route}_v${version}`);
    if (pausedStepId) {
        // If there's a paused tour, we'll resume it later
        tour.pausedStepId = pausedStepId;
    }

    return !localStorage.getItem(storageKey);
}


function restartTour(route, version = 1) {
    localStorage.removeItem(`tourCompleted_${route}_v${version}`);
    if (window.initTourGuide) {
        window.initTourGuide(route).then(() => {
            if (window.shepherdTour) {
                window.shepherdTour.start();
            }
        });
    }
}

function resolveAction(tour, action) {
    if (typeof action === 'function') return action;
    switch (action) {
        case 'next':
            return tour.next;
        case 'back':
            return tour.back;
        case 'cancel':
            return tour.cancel;
        default:
            return () => {
            };
    }
}

window.restartTour = restartTour;

/**
 * Automatically initialize and start the tour guide if not completed
 * @param {Object} config - Tour configuration object
 * @returns {Promise<void>}
 */
export async function autoStartTourGuide(config) {
    if (!config || !config.route) {
        console.warn('Invalid tour configuration');
        return;
    }

    const version = config.version || 1;
    const route = config.route;
    const storageKey = `tourCompleted_${route}_v${version}`;
    const pausedStepKey = `tourPaused_${route}_v${version}`;

    // Check if there's a paused tour or if the tour hasn't been completed yet
    const isPaused = localStorage.getItem(pausedStepKey);
    const isNotCompleted = !localStorage.getItem(storageKey);

    if (isPaused || isNotCompleted) {
        await initTourGuide(config);

        if (window.shepherdTour) {
            // Start the tour
            window.shepherdTour.start();

            // If there's a paused tour, show the specific step
            if (isPaused && window.shepherdTour.pausedStepId) {
                try {
                    // Get the step by ID
                    const stepId = parseInt(window.shepherdTour.pausedStepId, 10);
                    // Show the step
                    window.shepherdTour.show(stepId);
                    // Remove the paused state as we've resumed it
                    localStorage.removeItem(pausedStepKey);
                } catch (e) {
                    console.error('Error resuming tour from paused step:', e);
                }
            }
        }
    }
}
