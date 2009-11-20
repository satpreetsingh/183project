<?php 

	// Author:  Joseph Trapani
	// Date:	10/27/2009
	// General concept found on:  http://www.killersites.com/forums/topic/1843/oops-mysql-connecting-and-fetching-data/
       // Base class which acts as an ancestor class.
	// Instanciation example:  include '/var/www/localhost/htdocs/model/dbcon.php';
	//                         $dbData = new DBData("localhost","root","b4n4n4s","NoteShareSEP);

	
    class  DBConnect {
	
        protected $hostname,$username,$password,$db_name;
        
     /**
       * Constructor
       * @param String $hostname,$username,$password,$db_name. 
       * All information we need to provide whenever we connect to a database.
       */
        public function __construct($hostname,$username,$password,$db_name){

            $this->hostname = $hostname;
            $this->username = $username;
            $this->password = $password;
            $this->db_name  = $db_name;

        }
        
     /**
       * Connect to MySQL.
       * @return void
       */
        public function connectToMySQL(){
            $this->con = mysql_connect($this->hostname,$this->username,$this->password);
            if (!$this->con){
                die( "<br>Could not connect to MySQL" . mysql_error() . ".<br />");
            }
        }
      }      
      
     /**
       * Disconnect from MySQL.
       * @return void
       */	  
	   
	  function closeConnection(){
           mysql_close();
        }


	/*  
     * DBData class extends DBConnect
     */
    class DBData extends DBConnect{

    
	/**
       * Constructor
       * @param String $hostname,$username,$password,$db_name. 
       * All information we need to provide whenever we connect to a database.
       */
        public function __construct($hostname = "localhost",$username = "root",$password = "b4n4n4s",$db_name = "NoteShareSEP"){
		  
		  
		  // Call the parent constructor to set up the protected data fields.
		  parent::__construct($hostname,$username,$password,$db_name);	
          
		  
		  // Connect to the MySQL Server.
		  $this -> connectToMySQL();
		  
		  
		  // Select the database.
                $this -> selectDB();
		  

        }        

		
		public function selectDB(){
            $result = mysql_select_db($this->db_name,$this->con);
            if (!$result){
                die( "<br>Could not connect to database ' ". $this->db_name. " '  ".mysql_error() . ".<br />");
            }
        }
    }
    

    
?>