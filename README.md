# mysiar/array-validator

For usage check ValidatorTest.php file

# Usage
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
