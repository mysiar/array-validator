<?php
declare(strict_types=1);

namespace Mysiar\ArrayValidator\Tests;

use Mysiar\ArrayValidator\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ValidatorTest extends TestCase
{
    public function testValidatingArray(): void
    {
        $record = [1, 2, 3, 4, 5];

        $validator = new Validator();

        $validator->addArrayConstraint(new Callback(function($record, ExecutionContextInterface $context) {
            if (count($record) !== 5) {
                $context->addViolation('The array must contain 5 columns');
            }
        }));

        $result = $validator->validate($record);

        $this->assertTrue($result);

        $record = [1, 2, 3, 4, 5, 6];
        $result = $validator->validate($record);
        $this->assertFalse($result);
        $this->assertEquals('The array must contain 5 columns', $validator->getErrors()[0]);
    }

    public function testValidatingArrayElementsDate(): void
    {
        $record = [1, '2020-01-20', 3, 4, 5];
        $validator = new Validator();
        $validator->addArrayElementConstraint(1, new Date());
        $result = $validator->validate($record);
        $this->assertTrue($result);

        $validator->addArrayElementConstraint(2, new Date());
        $result = $validator->validate($record);
        $this->assertFalse($result);
        $this->assertEquals('index 2 : This value is not a valid date.', $validator->getErrors()[0]);
    }

    public function testValidatingArrayElementsEmail(): void
    {
        $record = [1, '2020-01-20', 'franek@dolas.com', 4, 5];
        $validator = new Validator();
        $validator->addArrayElementConstraint(1, new Date());
        $validator->addArrayElementConstraint(2, new Email());
        $result = $validator->validate($record);
        $this->assertTrue($result);

        $validator->addArrayElementConstraint(3, new Email());
        $result = $validator->validate($record);
        $this->assertFalse($result);
        $this->assertEquals('index 3 : This value is not a valid email address.', $validator->getErrors()[0]);
    }

    public function testValidatingArrayElementsNotBlank(): void
    {
        $record = [1, '2020-01-20', 'franek@dolas.com', '', 5];
        $validator = new Validator();
        $validator->addArrayElementConstraint(1, new Date());
        $validator->addArrayElementConstraint(2, new Email());
        $validator->addArrayElementConstraint(4, new NotBlank());
        $result = $validator->validate($record);
        $this->assertTrue($result);

        $validator->addArrayElementConstraint(3, new NotBlank());
        $result = $validator->validate($record);
        $this->assertFalse($result);
        $this->assertEquals('index 3 : This value should not be blank.', $validator->getErrors()[0]);
    }

    public function testValidatingArrayElementsInArray(): void
    {
        $record = [1, 0, 3, 4, 5];
        $validator = new Validator();
        $validator->addArrayElementConstraint(0, new Choice([0, 1]));
        $validator->addArrayElementConstraint(1, new Choice([0, 1]));
        $result = $validator->validate($record);
        $this->assertTrue($result);

        $validator->addArrayElementConstraint(2, new Choice([0, 1]));
        $result = $validator->validate($record);
        $this->assertFalse($result);
        $this->assertEquals('index 2 : The value you selected is not a valid choice.', $validator->getErrors()[0]);
    }
}
