#Transform PHP Vars to JavaScript in CodeIgniter Framework

Often, you'll find yourself in situations, where you want to pass some server-side string/array/collection/whatever to your JavaScript. Traditionally, this can be a bit of a pain - especially as your app grows.

This codeIgniter library simplifies the process drastically


#Installation

Download the files add put PHPtoJS.php class in your library folder<code>application\libraries\PHPtoJs.php</code>.Now load the library locally or globally.

## Locally

<pre>
  $this->load->library('PHPtoJS');
</pre>

## Globally 
Open you autoload.php file which located at <code>application\config\autoload.php</code>
<pre>
$autoload['libraries'] = array('PHPtoJS');
</pre>

After loading library,  you may use in your controllers as shown below.


<pre>
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
	</pre>
	
	Final step in your layout page add this line
	
	<pre>
	 echo $this->phptojs->getJsVars(); 
	</pre>
	
