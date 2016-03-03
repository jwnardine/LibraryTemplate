<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Author.php";
    require_once "src/Book.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    Class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Author::deleteAll();
            Book::deleteAll();
        }

       function testGetId()
        {
            //Arrange
            $first_name = "Matt";
            $last_name = "Smith";
            $id = 1;

            $test_author = new Author($first_name, $last_name, $id);
            //Act
            $result = $test_author->getId();
            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
       {
           //Arrange

           $first_name = "Matt";
           $last_name = "smith";
           $test_author = new Author($first_name, $last_name);
           //Act
           $test_author->save();
           //Assert
           $result = Author::getAll();

           $this->assertEquals($test_author, $result[0]);
       }

        function test_getAll()
         {
            //Arrange
            $first_name = "Matt";
            $last_name = "smith";
            $first_name2 = "Jon";
            $last_name2 = "smith";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();
            $test_author2 = new Author($first_name2, $last_name2);
            $test_author2->save();
            //Act
            $result = Author::getAll();
            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);
         }


        function testDeleteAll()
        {
            //Arrange
            $first_name = "Matt";
            $last_name = "Smith";
            $id = 1;
            $test_author = new Author($first_name, $last_name, $id);
            $test_author->save();

            $first_name2 = "John";
            $last_name2 = "Smith";
            $id = 2;
            $test_author2 = new Author($first_name2, $last_name2, $id);
            $test_author2->save();
            //Act
            Author::deleteAll();
            //Assert
            $result = Author::getAll();
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $first_name = "Matt";
            $last_name = "Smith";
            $test_author = new Author($first_name, $last_name);
            $test_author->save();
            $first_name2 = "Jon";
            $last_name2 = "Smith";
            $test_author2 = new Author($first_name2, $last_name2);
            $test_author2->save();
            //Act
            $result = Author::find($test_author->getId());
            //Assert
            $this->assertEquals($test_author, $result);
        }

        function testUpdate()
         {
            //Arrange
            $first_name = "Matt";
            $last_name = "Smith";
            $id = 1;
            $test_author = new Author($first_name, $last_name, $id);
            $test_author->save();
            $new_first_name = "Matthew";
            $new_last_name = "Smythe";
            //Act
            $test_author->update($new_first_name, $new_last_name);
            //Assert
            $this->assertEquals("Matthew", $test_author->getFirstName());
            $this->assertEquals("Smythe", $test_author->getLastName());
         }

         function testAddBook()
        {
            //Arrange
            $first_name = "Matt";
            $last_name = "Smith";
            $id = 1;
            $test_author = new Author($first_name, $last_name, $id);
            $test_author->save();

            $title = "How to Cook Steak";
            $id2 = 2;
            $test_book = new Book($title, $id2);
            $test_book->save();
            //Act
            $test_author->addBook($test_book);
            //Assert
            $this->assertEquals($test_author->getBooks(), [$test_book]);
        }

        function testGetBooks()
        {
        //Arrange
            $first_name = "Matt";
            $last_name = "Smith";
            $id = 1;
            $test_author = new Author($first_name, $last_name, $id);
            $test_author->save();

            $title = "Matt and his Guide to Stuff";
            $id2 = 2;
            $test_book = new Book($title, $id2);
            $test_book->save();

            $title2 = "Matt and his World";
            $id3 = 3;
            $test_book2 = new Book($title2, $id3);
            $test_book2->save();
            //Act
            $test_author->addBook($test_book);
            $test_author->addBook($test_book2);
            //Assert
            $this->assertEquals($test_author->getBooks(), [$test_book, $test_book2]);
        }

        function testDelete()
        {
            //Arrange
            $first_name = "Matt";
            $last_name = "Smith";
            $id = 1;
            $test_author = new Author($first_name, $last_name, $id);
            $test_author->save();

            $title = "Matt and his Guide to Stuff";
            $id2 = 2;
            $test_book = new Book($title, $id2);
            $test_book->save();

            //Act
            $test_author->addBook($test_book);
            $test_author->delete();
            //Assert
            $this->assertEquals([], $test_book->getAuthors());
        }
    }
?>
