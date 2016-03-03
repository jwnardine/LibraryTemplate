<?php
    class Author
    {
        private $first_name;
        private $last_name;
        private $id;

        function __construct($first_name, $last_name, $id = null)
        {
            $this->first_name = $first_name;
            $this->last_name = $last_name;
            $this->id = $id;
        }

        function setFirstName($new_first_name)
        {
            $this->first_name = (string) $new_first_name;
        }

        function getFirstName()
        {
            return $this->first_name;
        }

        function setLastName($new_last_name)
        {
            $this->last_name = (string) $new_last_name;
        }

        function getLastName()
        {
            return $this->last_name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
              $GLOBALS['DB']->exec("INSERT INTO authors (first_name, last_name)  VALUES ('{$this->getFirstName()}', '{$this->getLastName()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_first_name, $new_last_name)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET first_name = '{$new_first_name}', last_name = '{$new_last_name}' WHERE id = {$this->getId()};");
            $this->setFirstName($new_first_name);
            $this->setLastName($new_last_name);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE author_id = {$this->getId()};");
        }

        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors;");
            $authors = array();
            foreach($returned_authors as $author) {
                $first_name = $author['first_name'];
                $last_name = $author['last_name'];
                $id = $author['id'];
                $new_author = new Author($first_name, $last_name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors;");
        }

        static function find($search_id)
        {
            $found_author = null;
            $authors = Author::getAll();
            foreach($authors as $author) {
                $author_id = $author->getId();
                if ($author_id == $search_id) {
                  $found_author = $author;
                }
            }
            return $found_author;
        }

        function addBook($book)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) VALUES ({$this->getId()}, {$book->getId()});");
        }

        function getBooks()
        {

            $returned_books = $GLOBALS['DB']->query("SELECT books.* FROM
				authors JOIN authors_books ON (authors.id = authors_books.author_id)
								JOIN books ON (authors_books.book_id = books.id)
								WHERE authors.id = {$this->getId()};");
            // $query = $GLOBALS['DB']->query("SELECT book_id FROM authors_books WHERE author_id = {$this->getId()};");
            //
            // $book_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            $books = array();
//ON THE LINE BELOW CHANGE $book to $returned_book
            foreach($returned_books as $returned_book) {
                // $book_id = $id['book_id'];
                // // $result = $GLOBALS['DB']->query("SELECT * FROM books WHERE id = {$book_id};");
                // // $returned_book = $result->fetchAll(PDO::FETCH_ASSOC);
                $title = $returned_book['title'];
                $id = $returned_book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
          return $books;
        }
    }
?>
