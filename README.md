# mysiar/array-validator

For usage check ValidatorTest.php file

# Usage

* example
    ```php
    
    use Mysiar\ArrayValidator\Validator;
    use Symfony\Component\Validator\Constraints\Callback;
    use Symfony\Component\Validator\Constraints\Choice;
    use Symfony\Component\Validator\Constraints\Date;
    use Symfony\Component\Validator\Constraints\Email;
    use Symfony\Component\Validator\Constraints\NotBlank;
    use Symfony\Component\Validator\Constraints\Positive;
    use Symfony\Component\Validator\Constraints\PositiveOrZero;
    use Symfony\Component\Validator\Context\ExecutionContextInterface;
    
    $validator = new Validator();
    
    $validator->addArrayConstraint(new Callback(function($record, ExecutionContextInterface $context) {
        if (count($record) !== 15) {
            $context->addViolation(sprintf('The array must contain %s columns', self::FIELDS_COUNT));
        }
    }));
    
    $validator->addArrayElementConstraint(0, new Positive());
    $validator->addArrayElementConstraint(1, new NotBlank());
    $validator->addArrayElementConstraint(2, new NotBlank());
    $validator->addArrayElementConstraint(3, new NotBlank());
    $validator->addArrayElementConstraint(4, new NotBlank());
    $validator->addArrayElementConstraint(5, new Date());
    $validator->addArrayElementConstraint(6, new Positive());
    $validator->addArrayElementConstraint(7, new NotBlank());
    $validator->addArrayElementConstraint(8, new Email());
    $validator->addArrayElementConstraint(9, new Date());
    $validator->addArrayElementConstraint(10, new Positive());
    $validator->addArrayElementConstraint(12, new Choice([0, 1]));
    $validator->addArrayElementConstraint(13, new PositiveOrZero());
    $validator->addArrayElementConstraint(14, new PositiveOrZero());
    
    ```


* in case you use function `fgetcsv` to read csv file line by line to validate Choice of values in the array element you may need to use
    ```
    $validator->addArrayElementConstraint(2, new Choice(["0", "1"]));
    ```
    instead
    ```
    $validator->addArrayElementConstraint(2, new Choice([0, 1]));
    ```

### Notice
this lib is inspired by [deblan/csv-validator](https://gitnet.fr/deblan/csv-validator)
