<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class InvitationCodeTest extends KernelTestCase
{
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

    public function testDoubleEmail()
    {
        $this->getEntity();
        $this->assertHasErrors($this->getEntity2()->setEmail('Test_email@sym.fr'), 1);
    }

    /*
    public function testInvalidCodeEntity()
    {
        $this->assertHasErrors($this->getEntity()->setCode('1a345'), 1);
        $this->assertHasErrors($this->getEntity()->setCode('1345'), 1);
    }

    public function testInvalidBlankCodeEntity()
    {
        $this->assertHasErrors($this->getEntity()->setCode(''), 1);
    }

    public function testInvalidBlankDescriptionEntity()
    {
        $this->assertHasErrors($this->getEntity()->setDescription(''), 1);
    }

    public function testInvalidUsedCode ()
    {
        $this->loadFixtureFiles([dirname(__DIR__) . '/fixtures/invitation_codes.yaml']);
        $this->assertHasErrors($this->getEntity()->setCode('54321'), 1);
    }
*/
}
