<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Book.php";
    require_once "src/Author.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    Class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            Author::deleteAll();
        }

        function testGetTitle()
        {
            $title = "How to Cook a Steak";
            $test_book = new Book($title);

            $result = $test_book->getTitle();

            $this->assertEquals($title, $result);
        }

        function testSetTitle()
        {
           //Arrange
            $title = "How to Cook a Steak";
            $test_book = new Book($title);
            //Act
            $test_book->setTitle("How to Cook a Steak");
            $result = $test_book->getTitle();
            //Assert
            $this->assertEquals("How to Cook a Steak", $result);
        }

       function testGetId()
        {
            //Arrange
            $title = "How to Cook a Steak";
            $id = 1;

            $test_book = new Book($title, $id);
            //Act
            $result = $test_book->getId();
            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
       {
           //Arrange

            $title = "How to Cook a Steak";
            $test_book = new Book($title);
            //Act
            $test_book->save();
            //Assert
            $result = Book::getAll();

            $this->assertEquals($test_book, $result[0]);
       }

        function test_getAll()
         {
            //Arrange
            $title = "How to Cook a Steak";
            $title2 = "Jon";
            $test_book = new Book($title);
            $test_book->save();
            $test_book2 = new Book($title2);
            $test_book2->save();
            //Act
            $result = Book::getAll();
            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }


        function testDeleteAll()
        {
            //Arrange
            $title = "How to Cook a Steak";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Jon";
            $id2 = 2;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();
            //Act
            Book::deleteAll();
            //Assert
            $result = Book::getAll();
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $title = "How to Cook a Steak";
            $title2 = "Jon";
            $test_book = new Book($title);
            $test_book->save();
            $test_book2 = new Book($title2);
            $test_book2->save();
            //Act
            $result = Book::find($test_book->getId());
            //Assert
            $this->assertEquals($test_book, $result);
        }

        function testUpdate()
        {
            //Arrange
            $title = "How to Cook a Steak";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();
            $new_title = "How to Cook Chicken";
            //Act
            $test_book->update($new_title);
            //Assert
            $this->assertEquals("How to Cook Chicken", $test_book->getTitle());
        }

         function testAddAuthor()
        {
            //Arrange
            $first_name = "Matt";
            $last_name = "Smith";
            $id = 1;
            $test_author = new Author($first_name, $last_name, $id);
            $test_author->save();

            $title = "How to Cook a Steak";
            $id2 = 2;
            $test_book = new Book($title, $id2);
            $test_book->save();

            //Act
            $test_book->addAuthor($test_author);
            //Assert
            $this->assertEquals($test_book->getAuthors(), [$test_author]);
        }

        function testGetAuthors()
        {
        //Arrange
            $first_name = "Matt";
            $last_name = "Smith";
            $id = 1;
            $test_author = new Author($first_name, $last_name, $id);
            $test_author->save();

            $first_name2 = "Jon";
            $last_name2 = "Smith";
            $id = 1;
            $test_author2 = new Author($first_name2, $last_name2, $id);
            $test_author2->save();

            $title = "Matt and his Guide to Stuff";
            $id3 = 3;
            $test_book = new Book($title, $id3);
            $test_book->save();

            //Act
            $test_book->addAuthor($test_author);
            $test_book->addAuthor($test_author2);
            //Assert
            $this->assertEquals($test_book->getAuthors(), [$test_author, $test_author2]);
        }

        function testDelete()
        {
            //Arrange
            $first_name = "Matt";
            $last_name = "Smith";
            $id = 1;
            $test_author = new Author($first_name, $last_name, $id);
            $test_author->save();

            $title = "How to Cook a Steak";
            $id2 = 2;
            $test_book = new Book($title, $id2);
            $test_book->save();
            //Act
            $test_book->addAuthor($test_author);
            $test_book->delete();
            //Assert
            $this->assertEquals([], $test_author->getBooks());
        }

    }
?>
