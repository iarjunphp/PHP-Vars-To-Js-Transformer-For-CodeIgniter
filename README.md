#Transform PHP Vars to JavaScript in CodeIgniter Framework

Often, you'll find yourself in situations, where you want to pass some server-side string/array/collection/whatever to your JavaScript. Traditionally, this can be a bit of a pain - especially as your app grows.

This codeIgniter library simplifies the process drastically


#Installation

Download the files add put PHPtoJS.php class in your library folder<code>application\libraries\PHPtoJs.php</code>.Now load the library locally or globally.

## Locally


```
  $this->load->library('PHPtoJS');

```

## Globally 
Open you autoload.php file which located at <code>application\config\autoload.php</code>

```
$autoload['libraries'] = array('PHPtoJS');

```

After loading library,  you may use in your controllers as shown below.



```
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {


	public function index()
	{
            
            $this->load->library('PHPtoJS',['namespace' => 'arjun']);
            $this->phptojs->put([
                'foo' => 'bar',
                'age' => 29
            ]);
                       
	    $this->load->view('welcome_message');
	}
}

```
	
Final step in your layout page add this line
	

```
	 echo $this->phptojs->getJsVars(); 

```
	
