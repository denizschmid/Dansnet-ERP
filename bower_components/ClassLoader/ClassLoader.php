<?php
/********************************************************************************
 * @author	Deniz Schmid
 * @descr	Diese Klasse regelt das automatische Laden von Klassen. Ausgangspunkt
 * 			ist immer ein Verzeichnis. Hier kann entschieden werden, ob nur dieses
 *			Verzeichnis nach Klassen oder auch Unterverezichnisse rekursiv
 *			durchsucht werden.
 *
 * @crdate 2015-03-27
 *
 ********************************************************************************/
	class ClassLoader
	{
            
            /*
             * Wenn true, werden ausgehen vom Basisverzeichnis alle Verzeichnisse
             * nach der Klasse durchsucht.
             * 
             * @var boolean
             */
            private $_recursive;
            
            /*
             * Basisverzeichnis, in dem die zu ladende Klasse gesucht wird.
             * 
             * @var string
             */
            private $_baseDir;
            
            /*
             * Suffix der zu ladenden Datei der Klasse (z.B. '.class.php'.
             * 
             * @var string
             */
            private $_suffix;
            
            /********************************************************************************
             * @author	Deniz Schmid
             * @descr	Konstruktor: Setzt Basispfad, in dem nach der zu ladenden
             *          Klasse gesucht werden soll, und gibt an, ob Unterverzeichnisse
             *          ebenfalls rekursiv durchsucht werden sollen.
             *
             * @crdate 	2015-03-29
             *
             * @param  	string 	BaseDir   Basispfad, in dem gesucht werden soll.
             * @param  	boolean	Recursive Wenn true, werden Unterverzeichnisse 
             *                            ebenfalls durchsucht.
             * @param  	string	Suffix    Endung der zu ladenden Datei. Standard 
             *                            ist '.php'.
             * @return  void
             ********************************************************************************/
            public function __construct( $BaseDir, $Recursive=TRUE, $Suffix=".php" ) 
            {
                $this->_recursive = $Recursive;
                $this->_baseDir = $BaseDir;
                $this->_suffix = $Suffix;
                spl_autoload_register( array( $this, 'LoadClassBase' ) );
            }
            
            /********************************************************************************
             * @author	Deniz Schmid
             * @descr	Lädt eine Klasse anhand des Klassennamen. Der Klassenname
             *          entspricht dabei dem Dateinamen und der Endung .php.
             *
             * @crdate 	2015-03-29
             *
             * @param  	string 	ClassName   Klassenname der Klasse, die geladen
             * @return  boolean TRUE, falls Klasse geladen wurde, sonst FALSE.
             ********************************************************************************/
            public function LoadClassBase ( $ClassName )
            {
               return $this->LoadClass($ClassName);
            }
            
            /********************************************************************************
             * @author	Deniz Schmid
             * @descr	Lädt eine Klasse anhand des Klassennamen. Der Klassenname
             *          entspricht dabei dem Dateinamen und der Endung .php.
             *
             * @crdate 	2015-03-29
             *
             * @param  	string 	ClassName   Klassenname der Klasse, die geladen
             *                              werden soll.
             * @param   string  Directory   Pfad, in dem gesucht werden soll. Falls
             *                              nicht angegeben, wird der Basispfad
             *                              verwendet. Der Parameter wird hauptsächlich
             *                              in Kombination mit der Rekursion genutzt.
             * @return  boolean TRUE, falls Klasse geladen wurde, sonst FALSE.
             ********************************************************************************/
            public function LoadClass( $ClassName, $Directory=NULL )
            {
                $ReadDir = ( $Directory==NULL ? $this->_baseDir : $Directory );
		
                $ClassName = end(explode('\\', $ClassName));	
		
		
                // Lade Datei im Basisverzeichnis
                if( file_exists( "$ReadDir/$ClassName$this->_suffix") )
                {
		    require_once "$ReadDir/$ClassName$this->_suffix";
                    return TRUE;
                }
		
                // Falls keine gefunden wurde suche rekursiv in Unterverzeichnissen
                // falls gewünscht.
                elseif( $this->_recursive )
                {
                    foreach( scandir($ReadDir) as $dir )
                    {			
			if( $dir === "." || $dir === ".." || !is_dir("$ReadDir/$dir") ) continue;
                        $this->LoadClass($ClassName, "$ReadDir/$dir");
                    }
                }

                return FALSE;
                
            }
           
	}