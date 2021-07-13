<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\DataFixtures\DataFixtureTestCase;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\ConstraintViolation;

class UserTest extends DataFixtureTestCase
{
    public function testGetId()
    {
        $user = new User();
        static::assertEquals($user->getId(), null);
    }

    public function testGetSetUsername()
    {
        $user = new User();
        $user->setUsername('Test username');
        static::assertEquals($user->getUsername(), 'Test username');
    }

    public function testGetSetPassword()
    {
        $user = new User();
        $user->setPassword('Test password');
        static::assertEquals($user->getPassword(), 'Test password');
    }

    public function testGetSetEmail()
    {
        $user = new User();
        $user->setEmail('Test email');
        static::assertEquals($user->getEmail(), 'Test email');
    }
    
    public function testGetAddTask()
    {
        $user = new User();
        static::assertInstanceOf(User::class, $user->AddTask(new Task()));
        static::assertInstanceOf(ArrayCollection::class, $user->getTasks());
        static::assertContainsOnlyInstancesOf(Task::class, $user->getTasks());
    }

    public function testRemoveTask()
    {
        $user = new User();
        // If there is not the Task in the ArrayCollection
        static::assertInstanceOf(User::class, $user->removeTask(new Task()));
        static::assertEmpty($user->getTasks());

        // If there is the Task in the ArrayCollection
        $task = new Task();
        $user->addTask($task);
        $user->removeTask($task);
        static::assertEmpty($user->getTasks());
        static::assertInstanceOf(User::class, $user->removeTask(new Task()));
    }

    public function testGetSalt()
    {
        $user = new User();
        static::assertEquals($user->getSalt(), null);
    }

    public function testEraseCredentials()
    {
        $user = new User();
        static::assertEquals($user->eraseCredentials(), null);
    }

    public function testGetSetRoles()
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        static::assertEquals($user->getRoles(), ['ROLE_ADMIN']);
    }



    public function getEntity(): User
    {
        return (new User())
            ->setUsername('Test Name')
            ->setEmail('Test_email@sym.fr');
    }

    public function getEntity2(): User
    {
        return (new User())
            ->setUsername('Test Name 2');
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }
    
    public function testWrongEmail()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('Test email'), 1);
    }
}
