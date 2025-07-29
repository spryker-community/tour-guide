class Tour {
    constructor(options = {}) {
        this.options = options;
        this.steps = [];
        this.currentStep = null;
        this.currentStepIndex = -1;
        this.events = {};
        this.pausedStepId = null;
        this.currentTargetElement = null;
        this.modalOverlay = document.createElement('div');
        this.modalOverlay.className = 'tour-modal-overlay-container';

        document.body.appendChild(this.modalOverlay);
    }

    /**
     * Add a step to the tour
     * @param {Object} options - Step options
     */
    addStep(options) {
        const mergedOptions = this.options.defaultStepOptions
            ? { ...this.options.defaultStepOptions, ...options }
            : options;

        const step = {
            id: this.steps.length,
            options: mergedOptions,
            tour: this,
            isOpen: false,
            element: null
        };

        this.steps.push(step);
        return step;
    }

    /**
     * Start the tour
     */
    start() {
        this.trigger('start');
        if (this.steps.length > 0) {
            this.show(0);
        }
    }

    /**
     * Show a specific step
     * @param {number} stepId - The step ID to show
     */
    show(stepId) {
        const stepIndex = typeof stepId === 'number' ? stepId : parseInt(stepId, 10);
        if (stepIndex < 0 || stepIndex >= this.steps.length) {
            return;
        }

        if (this.options.useModalOverlay) {
            this.modalOverlay.classList.add('tour-modal-is-visible');
        }

        if (this.currentStep) {
            this.hideStep(this.currentStep);
        }

        this.currentStepIndex = stepIndex;
        this.currentStep = this.steps[stepIndex];
        this.showStep(this.currentStep);
        this.trigger('show', this.currentStep);
    }

    /**
     * Hide the tour
     */
    hide() {
        if (this.currentStep) {
            this.hideStep(this.currentStep);
            this.currentStep = null;
        }
        this.modalOverlay.classList.remove('tour-modal-is-visible');
    }

    /**
     * Go to the next step
     */
    next() {
        if (this.currentStepIndex < this.steps.length - 1) {
            this.show(this.currentStepIndex + 1);
        }
    }

    /**
     * Go to the previous step
     */
    back() {
        if (this.currentStepIndex > 0) {
            this.show(this.currentStepIndex - 1);
        }
    }

    /**
     * Complete the tour
     */
    complete() {
        this.hide();
        this.trigger('complete');
    }

    /**
     * Cancel the tour
     */
    cancel() {
        this.hide();
        this.trigger('cancel');
    }

    /**
     * Get the current step
     * @returns {Object} The current step
     */
    getCurrentStep() {
        return this.currentStep;
    }

    /**
     * Register an event handler
     * @param {string} event - Event name
     * @param {Function} handler - Event handler
     */
    on(event, handler) {
        if (!this.events[event]) {
            this.events[event] = [];
        }
        this.events[event].push(handler);
    }

    /**
     * Trigger an event
     * @param {string} event - Event name
     * @param {...any} args - Event arguments
     */
    trigger(event, ...args) {
        if (this.events[event]) {
            this.events[event].forEach(handler => handler(...args));
        }
    }

    /**
     * Show a step
     * @param {Object} step - The step to show
     */
    async showStep(step) {
        if (!step.element) {
            step.element = this.createStepElement(step);
            document.body.appendChild(step.element);
        }

        await this.positionStep(step);

        step.element.classList.add('tour-enabled');
        step.isOpen = true;
    }

    /**
     * Highlight the target element
     * @param {HTMLElement} targetElement - The element to highlight
     */
    highlightTargetElement(targetElement) {
        if (targetElement) {
            this.unhighlightTargetElement();

            targetElement.classList.add('tour-target');
            targetElement.classList.add('tour-enabled');

            this.currentTargetElement = targetElement;
        }
    }

    /**
     * Remove highlight from the current target element
     */
    unhighlightTargetElement() {
        if (this.currentTargetElement) {
            this.currentTargetElement.classList.remove('tour-target');
            this.currentTargetElement.classList.remove('tour-enabled');
            this.currentTargetElement = null;
        }
    }

    /**
     * Hide a step
     * @param {Object} step - The step to hide
     */
    hideStep(step) {
        if (step.element) {
            step.element.classList.remove('tour-enabled');
            step.isOpen = false;
        }

        this.unhighlightTargetElement();
    }

    /**
     * Create a step element
     * @param {Object} step - The step
     * @returns {HTMLElement} The step element
     */
    createStepElement(step) {
        const { options } = step;
        const element = document.createElement('div');
        element.className = 'tour-element';

        if (options.classes) {
            options.classes.split(' ').forEach(className => {
                element.classList.add(className);
            });
        }

        const content = document.createElement('div');
        content.className = 'tour-content';
        element.appendChild(content);

        const header = document.createElement('div');
        header.className = 'tour-header';
        content.appendChild(header);

        if (options.title) {
            element.classList.add('tour-has-title');

            const title = document.createElement('h3');
            title.className = 'tour-title';
            title.textContent = options.title;
            header.appendChild(title);
        }

        if (options.cancelIcon && options.cancelIcon.enabled) {
            const cancelButton = document.createElement('button');
            cancelButton.className = 'tour-cancel-icon';
            cancelButton.innerHTML = '&times;';
            cancelButton.addEventListener('click', () => this.cancel());
            header.appendChild(cancelButton);
        }

        if (options.text) {
            const text = document.createElement('div');
            text.className = 'tour-text';
            text.innerHTML = options.text;
            content.appendChild(text);
        }

        if (options.buttons && options.buttons.length > 0) {
            const footer = document.createElement('div');
            footer.className = 'tour-footer';
            content.appendChild(footer);

            options.buttons.forEach(buttonConfig => {
                const button = document.createElement('button');
                button.className = 'tour-button';
                if (buttonConfig.classes) {
                    buttonConfig.classes.split(' ').forEach(className => {
                        button.classList.add(className);
                    });
                }
                button.textContent = buttonConfig.text;
                button.addEventListener('click', () => {
                    if (typeof buttonConfig.action === 'function') {
                        buttonConfig.action.call(this);
                    }
                });
                footer.appendChild(button);
            });
        }

        const arrow = document.createElement('div');
        arrow.className = 'tour-arrow';
        element.appendChild(arrow);

        return element;
    }

    /**
     * Position a step relative to its target element
     * @param {Object} step - The step to position
     */
    async positionStep(step) {
        const { options } = step;

        if (options.beforeShowPromise) {
            await options.beforeShowPromise(step);
        }

        if (options.attachTo && options.attachTo.element) {
            const targetElement = document.querySelector(options.attachTo.element);
            if (targetElement) {
                this.highlightTargetElement(targetElement);

                const position = options.attachTo.on || 'bottom';
                this.positionElementToTarget(step.element, targetElement, position);

                if (options.scrollTo) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        } else {
            this.centerStep(step.element);
        }
    }

    /**
     * Position an element relative to a target
     * @param {HTMLElement} element - The element to position
     * @param {HTMLElement} target - The target element
     * @param {string} position - The position (top, bottom, left, right)
     */
    positionElementToTarget(element, target, position) {
        const targetRect = target.getBoundingClientRect();
        const elementRect = element.getBoundingClientRect();

        element.setAttribute('data-popper-placement', position);

        let top, left;

        switch (position) {
            case 'top':
                top = targetRect.top - elementRect.height - 16;
                left = targetRect.left + (targetRect.width / 2) - (elementRect.width / 2);
                break;
            case 'bottom':
                top = targetRect.bottom + 16;
                left = targetRect.left + (targetRect.width / 2) - (elementRect.width / 2);
                break;
            case 'left':
                top = targetRect.top + (targetRect.height / 2) - (elementRect.height / 2);
                left = targetRect.left - elementRect.width - 16;
                break;
            case 'right':
                top = targetRect.top + (targetRect.height / 2) - (elementRect.height / 2);
                left = targetRect.right + 16;
                break;
            default:
                top = targetRect.bottom + 16;
                left = targetRect.left + (targetRect.width / 2) - (elementRect.width / 2);
        }

        top += window.scrollY;
        left += window.scrollX;

        element.style.top = `${top}px`;
        element.style.left = `${left}px`;
    }

    /**
     * Center a step in the viewport
     * @param {HTMLElement} element - The element to center
     */
    centerStep(element) {
        element.classList.add('tour-centered');
        element.style.top = '50%';
        element.style.left = '50%';
        element.style.transform = 'translate(-50%, -50%)';
    }
}

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

async function trackTourEvent(idTourGuide, eventType, tourVersion) {
    try {
        const response = await fetch(`/tour-guide/event/track`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                idTourGuide: idTourGuide,
                tourVersion: tourVersion,
                eventName: eventType
            })
        });

        if (!response.ok) {
            console.error(`Error tracking tour ${eventType} event:`, response.statusText);
        }

        return response.ok;
    } catch (e) {
        console.error(`Error tracking tour ${eventType} event:`, e);
        return false;
    }
}

function createPauseButton(tour, route, version) {
    if (!document.getElementById('tour-pause-button-styles')) {
        const styleEl = document.createElement('style');
        styleEl.id = 'tour-pause-button-styles';
        document.head.appendChild(styleEl);
    }

    const addPauseButtonToFooter = () => {
        setTimeout(() => {
            const footers = document.querySelectorAll('.tour-footer');

            if (footers.length > 0) {
                const currentFooter = footers[footers.length - 1];

                if (!currentFooter.querySelector('.tour-pause-button')) {
                    const pauseButton = document.createElement('button');
                    pauseButton.className = 'tour-pause-button';
                    pauseButton.textContent = 'Pause';
                    pauseButton.title = 'Pause Tour';

                    pauseButton.addEventListener('click', () => {
                        const currentStepIndex = tour.getCurrentStep().id;
                        localStorage.setItem(`tourPaused_${route}_v${version}`, currentStepIndex);
                        if (window.tourConfig && window.tourConfig.idTourGuide) {
                            trackTourEvent(window.tourConfig.idTourGuide, 'pause', version);
                        }
                        tour.hide();
                    });

                    currentFooter.insertBefore(pauseButton, currentFooter.firstChild);
                }
            }
        }, 50);
    };

    tour.on('show', addPauseButtonToFooter);

    return addPauseButtonToFooter;
}

/**
 * Wait for an element to be present in the DOM
 * @param {string} selector - CSS selector for the element
 * @param {number} timeout - Maximum time to wait in milliseconds
 * @returns {Promise<Element|null>} - The found element or null if not found
 */
function waitForElement(selector, timeout = 3000) {
    return new Promise((resolve) => {
        const element = document.querySelector(selector);
        if (element) {
            resolve(element);
            return;
        }

        const timeoutId = setTimeout(() => {
            console.warn(`Timeout waiting for element: ${selector}`);
            resolve(null);
        }, timeout);

        const observer = new MutationObserver((mutations, obs) => {
            const element = document.querySelector(selector);
            if (element) {
                clearTimeout(timeoutId);
                obs.disconnect();
                resolve(element);
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
}

/**
 * Creates a button in the footer to start the tour
 * @param {string} route - The route for the tour
 * @param {number} version - The version of the tour
 */
function createTourStartButton(route, version = 1) {
    if (document.getElementById('tour-start-button')) {
        return;
    }

    waitForElement('.footer').then(footer => {
        if (footer) {
            const startButton = document.createElement('i');
            startButton.id = 'tour-start-button';
            startButton.classList.add('fa', 'fa-map-signs');
            startButton.title = 'Start Guided Tour';

            startButton.addEventListener('click', () => {
                if (window.guidedTour) {
                    window.guidedTour.start();
                } else {
                    restartTour(route, version);
                }
            });

            footer.appendChild(startButton);
        }
    });
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

    const tour = new Tour({
        useModalOverlay: true,
        useHistory: true,
        defaultStepOptions: {
            cancelIcon: {enabled: true},
            classes: 'tour-theme-default',
            scrollTo: true,
            ...defaultStepOptions,
            beforeShowPromise: function(step) {
                return new Promise((resolve) => {
                    if (step && step.options && step.options.attachTo && step.options.attachTo.element) {
                        const selector = step.options.attachTo.element;
                        waitForElement(selector).then(() => {
                            resolve();
                        });
                    } else {
                        resolve();
                    }
                });
            }
        }
    });

    steps.forEach((step, index) => {
        const isFirstStep = index === 0;
        const isLastStep = index === steps.length - 1;
        const buttons = [];

        if (!isFirstStep) {
            buttons.push({
                text: 'Back',
                classes: 'tour-button-secondary',
                action: tour.back
            });
        }

        if (!isLastStep) {
            buttons.push({
                text: 'Next',
                classes: 'tour-button-primary',
                action: tour.next
            });
        } else {
            buttons.push({
                text: 'Finish',
                classes: 'tour-button-primary',
                action: tour.complete
            });
        }

        tour.addStep({
            ...step,
            buttons: buttons
        });
    });

    const storageKey = `tourCompleted_${route}_v${version}`;

    tour.on('complete', () => {
        localStorage.setItem(storageKey, 'true');
        localStorage.removeItem(`tourPaused_${route}_v${version}`);
        if (window.tourConfig && window.tourConfig.idTourGuide) {
            trackTourEvent(window.tourConfig.idTourGuide, 'finish', version);
        }
    });

    tour.on('cancel', () => {
        localStorage.setItem(storageKey, 'true');
        localStorage.removeItem(`tourPaused_${route}_v${version}`);
        if (window.tourConfig && window.tourConfig.idTourGuide) {
            trackTourEvent(window.tourConfig.idTourGuide, 'cancel', version);
        }
    });

    tour.on('start', () => {
        createPauseButton(tour, route, version);

        if (window.tourConfig && window.tourConfig.idTourGuide) {
            trackTourEvent(window.tourConfig.idTourGuide, 'start', version);
        }
    });

    window.guidedTour = tour;

    const pausedStepId = localStorage.getItem(`tourPaused_${route}_v${version}`);
    if (pausedStepId) {
        tour.pausedStepId = pausedStepId;
    }

    createTourStartButton(route, version);

    return !localStorage.getItem(storageKey);
}


function restartTour(route, version = 1) {
    localStorage.removeItem(`tourCompleted_${route}_v${version}`);
    if (window.initTourGuide) {
        window.initTourGuide(route).then(() => {
            if (window.guidedTour) {
                window.guidedTour.start();
            }
        });
    }
}

window.restartTour = restartTour;

/**
 * Wait for AJAX requests to complete and DOM to be fully loaded
 * @param {number} timeout - Maximum time to wait in milliseconds
 * @returns {Promise<void>}
 */
function waitForPageLoad(timeout = 5000) {
    return new Promise((resolve) => {
        if (document.readyState === 'complete') {
            setTimeout(resolve, 1500);
            return;
        }

        const timeoutId = setTimeout(() => {
            console.warn('Timeout waiting for page to fully load');
            resolve();
        }, timeout);

        window.addEventListener('load', () => {
            setTimeout(() => {
                clearTimeout(timeoutId);
                resolve();
            }, 1500);
        }, { once: true });
    });
}

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

    const isPaused = localStorage.getItem(pausedStepKey);
    const isNotCompleted = !localStorage.getItem(storageKey);

    await waitForPageLoad();
    await initTourGuide(config);

    if (isPaused || isNotCompleted) {
        if (window.guidedTour) {
            window.guidedTour.start();

            if (isPaused && window.guidedTour.pausedStepId) {
                try {
                    const stepId = parseInt(window.guidedTour.pausedStepId, 10);
                    window.guidedTour.show(stepId);
                    localStorage.removeItem(pausedStepKey);
                } catch (e) {
                    console.error('Error resuming tour from paused step:', e);
                }
            }
        }
    }
}
