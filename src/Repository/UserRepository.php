<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\GlobalServices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{

    /** @var GlobalServices */
    private $globalServices;

    public function __construct(ManagerRegistry $registry, GlobalServices $globalServices)
    {
        parent::__construct($registry, User::class);
        $this->globalServices = $globalServices;
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    public function getUniqueRoles(): array
    {
        $users = $this->createQueryBuilder('u')
            ->select('u.roles')
            ->getQuery()
            ->execute();

        $roles = [];

        foreach ($users as $user) {
            if ($user['roles']) {
                foreach ($user as $role) {
                    if (count($role) > 1) {
                        foreach ($role as $i) {
                            array_push($roles, $i);
                        }
                    } else {
                        array_push($roles, $role[0]);
                    }
                }
            }
        }

        if (!in_array("ROLE_USER", $roles)){
            array_push($roles, "ROLE_USER");
        }

        foreach ($roles as $oldKey => $role) {
            $newKey = substr($role, 5);
            $roles= $this->globalServices->replaceKeys($oldKey, $newKey, $roles);        
        }

        return array_unique($roles);
    }
}
