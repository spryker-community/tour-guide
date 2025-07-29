<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace SprykerCommunityTest\Zed\TourGuide\Business\Reader;

use Generated\Shared\Transfer\AclGroupsTransfer;
use Generated\Shared\Transfer\AclGroupTransfer;
use Generated\Shared\Transfer\GroupTransfer;
use Generated\Shared\Transfer\TourGuideCollectionTransfer;
use Generated\Shared\Transfer\TourGuideCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepCollectionTransfer;
use Generated\Shared\Transfer\TourGuideStepCriteriaTransfer;
use Generated\Shared\Transfer\TourGuideStepTransfer;
use Generated\Shared\Transfer\TourGuideTransfer;
use Generated\Shared\Transfer\UserTransfer;
use PHPUnit\Framework\TestCase;
use Spryker\Zed\Acl\Business\AclFacadeInterface;
use Spryker\Zed\User\Business\UserFacadeInterface;
use SprykerCommunity\Zed\TourGuide\Business\Reader\TourGuideReader;
use SprykerCommunity\Zed\TourGuide\Persistence\TourGuideRepositoryInterface;

final class TourGuideReaderTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetTourGuideCollectionReturnsCollectionFromRepository(): void
    {
        // Arrange
        $tourGuideCriteriaTransfer = new TourGuideCriteriaTransfer();
        $expectedCollection = new TourGuideCollectionTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('getTourGuideCollection')
            ->with($tourGuideCriteriaTransfer)
            ->willReturn($expectedCollection);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualCollection = $tourGuideReader->getTourGuideCollection($tourGuideCriteriaTransfer);

        // Assert
        $this->assertSame($expectedCollection, $actualCollection);
    }

    /**
     * @return void
     */
    public function testFindTourGuideByIdReturnsTransferFromRepository(): void
    {
        // Arrange
        $idTourGuide = 1;
        $expectedTransfer = new TourGuideTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideById')
            ->with($idTourGuide)
            ->willReturn($expectedTransfer);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideById($idTourGuide);

        // Assert
        $this->assertSame($expectedTransfer, $actualTransfer);
    }

    /**
     * @return void
     */
    public function testFindTourGuideByIdReturnsNullWhenNotFound(): void
    {
        // Arrange
        $idTourGuide = 999;

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideById')
            ->with($idTourGuide)
            ->willReturn(null);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideById($idTourGuide);

        // Assert
        $this->assertNull($actualTransfer);
    }

    /**
     * @return void
     */
    public function testFindTourGuideByRouteReturnsTransferFromRepositoryWhenNoAclGroup(): void
    {
        // Arrange
        $route = '/test-route';
        $expectedTransfer = new TourGuideTransfer();
        $expectedTransfer->setAclGroup(null);
        $expectedTransfer->setFkAclGroup(null);

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideByRoute')
            ->with($route)
            ->willReturn($expectedTransfer);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        // Setup user facade mock to return a user
        $userTransfer = new UserTransfer();
        $userTransfer->setIdUser(1);
        $userFacadeMock->method('getCurrentUser')->willReturn($userTransfer);

        // Setup acl facade mock
        $aclGroupsTransfer = new AclGroupsTransfer();
        $aclFacadeMock->method('getUserGroups')->willReturn($aclGroupsTransfer);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideByRoute($route);

        // Assert
        $this->assertSame($expectedTransfer, $actualTransfer);
    }

    /**
     * @return void
     */
    public function testFindTourGuideByRouteWithAclGroupReturnsTransferWhenUserHasAccess(): void
    {
        // Arrange
        $route = '/test-route';
        $aclGroupId = 1;
        $userId = 1;

        $expectedTransfer = new TourGuideTransfer();
        $groupTransfer = new GroupTransfer();
        $groupTransfer->setName('TestGroup');
        $expectedTransfer->setAclGroup($groupTransfer);
        $expectedTransfer->setFkAclGroup($aclGroupId);

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideByRoute')
            ->with($route)
            ->willReturn($expectedTransfer);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        // Setup user facade mock to return a user
        $userTransfer = new UserTransfer();
        $userTransfer->setIdUser($userId);
        $userFacadeMock->method('getCurrentUser')->willReturn($userTransfer);

        // Setup acl facade mock to return groups with matching ID
        $aclGroupsTransfer = new AclGroupsTransfer();
        $aclGroupTransfer = new AclGroupTransfer();
        $aclGroupTransfer->setIdAclGroup($aclGroupId);
        $aclGroupsTransfer->addGroup($aclGroupTransfer);
        $aclFacadeMock->method('getUserGroups')->with($userId)->willReturn($aclGroupsTransfer);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideByRoute($route);

        // Assert
        $this->assertSame($expectedTransfer, $actualTransfer);
    }

    /**
     * @return void
     */
    public function testFindTourGuideByRouteWithAclGroupReturnsNullWhenUserDoesNotHaveAccess(): void
    {
        // Arrange
        $route = '/test-route';
        $aclGroupId = 1;
        $userId = 1;

        $tourGuideTransfer = new TourGuideTransfer();
        $groupTransfer = new GroupTransfer();
        $groupTransfer->setName('TestGroup');
        $tourGuideTransfer->setAclGroup($groupTransfer);
        $tourGuideTransfer->setFkAclGroup($aclGroupId);

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideByRoute')
            ->with($route)
            ->willReturn($tourGuideTransfer);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        // Setup user facade mock to return a user
        $userTransfer = new UserTransfer();
        $userTransfer->setIdUser($userId);
        $userFacadeMock->method('getCurrentUser')->willReturn($userTransfer);

        // Setup acl facade mock to return groups with non-matching ID
        $aclGroupsTransfer = new AclGroupsTransfer();
        $aclGroupTransfer = new AclGroupTransfer();
        $aclGroupTransfer->setIdAclGroup(2); // Different from the tour guide's ACL group
        $aclGroupsTransfer->addGroup($aclGroupTransfer);
        $aclFacadeMock->method('getUserGroups')->with($userId)->willReturn($aclGroupsTransfer);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideByRoute($route);

        // Assert
        $this->assertNull($actualTransfer);
    }

    /**
     * @return void
     */
    public function testFindTourGuideByRouteReturnsNullWhenNotFound(): void
    {
        // Arrange
        $route = '/non-existent-route';

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideByRoute')
            ->with($route)
            ->willReturn(null);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideByRoute($route);

        // Assert
        $this->assertNull($actualTransfer);
    }

    /**
     * @return void
     */
    public function testGetTourGuideStepCollectionReturnsCollectionFromRepository(): void
    {
        // Arrange
        $tourGuideStepCriteriaTransfer = new TourGuideStepCriteriaTransfer();
        $expectedCollection = new TourGuideStepCollectionTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('getTourGuideStepCollection')
            ->with($tourGuideStepCriteriaTransfer)
            ->willReturn($expectedCollection);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualCollection = $tourGuideReader->getTourGuideStepCollection($tourGuideStepCriteriaTransfer);

        // Assert
        $this->assertSame($expectedCollection, $actualCollection);
    }

    /**
     * @return void
     */
    public function testFindTourGuideStepByIdReturnsTransferFromRepository(): void
    {
        // Arrange
        $idTourGuideStep = 1;
        $expectedTransfer = new TourGuideStepTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideStepById')
            ->with($idTourGuideStep)
            ->willReturn($expectedTransfer);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideStepById($idTourGuideStep);

        // Assert
        $this->assertSame($expectedTransfer, $actualTransfer);
    }

    /**
     * @return void
     */
    public function testFindTourGuideStepByIdReturnsNullWhenNotFound(): void
    {
        // Arrange
        $idTourGuideStep = 999;

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('findTourGuideStepById')
            ->with($idTourGuideStep)
            ->willReturn(null);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualTransfer = $tourGuideReader->findTourGuideStepById($idTourGuideStep);

        // Assert
        $this->assertNull($actualTransfer);
    }

    /**
     * @return void
     */
    public function testGetTourGuideStepsByRouteReturnsCollectionFromRepository(): void
    {
        // Arrange
        $route = '/test-route';
        $expectedCollection = new TourGuideStepCollectionTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('getTourGuideStepsByRoute')
            ->with($route)
            ->willReturn($expectedCollection);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualCollection = $tourGuideReader->getTourGuideStepsByRoute($route);

        // Assert
        $this->assertSame($expectedCollection, $actualCollection);
    }

    /**
     * @return void
     */
    public function testGetTourGuideStepsByTourGuideIdReturnsCollectionFromRepository(): void
    {
        // Arrange
        $idTourGuide = 1;
        $expectedCollection = new TourGuideStepCollectionTransfer();

        $tourGuideRepositoryMock = $this->createMock(TourGuideRepositoryInterface::class);
        $tourGuideRepositoryMock->expects($this->once())
            ->method('getTourGuideStepsByTourGuideId')
            ->with($idTourGuide)
            ->willReturn($expectedCollection);

        $userFacadeMock = $this->createMock(UserFacadeInterface::class);
        $aclFacadeMock = $this->createMock(AclFacadeInterface::class);

        $tourGuideReader = new TourGuideReader($tourGuideRepositoryMock, $userFacadeMock, $aclFacadeMock);

        // Act
        $actualCollection = $tourGuideReader->getTourGuideStepsByTourGuideId($idTourGuide);

        // Assert
        $this->assertSame($expectedCollection, $actualCollection);
    }
}
