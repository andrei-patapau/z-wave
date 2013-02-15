<?php


// Quick way to add your IP Address to 
// the allowedIPs list
// see below for details or to add multiple
// IPs.
$MyIPAddress = '127.0.0.1'; 

/* 	PHPsh
**	Copyright (C) 2005 P. Deegan. All Rights Reserved.
**
**
**	PHPsh allows you to have shell commands run on your behalf by 
**	any webserver which serves PHP pages.
**
**	***READ*** the license and additional info below.  Enjoy :-)
**
**	================  LICENSE/CONDITIONS OF USE   ==============
**	
**	You may use and modify this Program for personal or 
**	professional activities under the following four (4) 
**	conditions:
**	
**	 1) You do not modify the licensing terms or copyright notices,
**	including those visible on the program's output (page footer, 
**	etc.).
**
**	 2) You do not redistribute this Program but instead refer 
**	any other users to the PHPsh homepage 
**	(http://www.psychogenic.com/en/products/PHPsh.php).
**
**	 3) You only use this software to access data and perform 
**	activities for which you have legal rights (be nice).
**
**	 4) You read and accept the following "NO WARRANTY" clause:
**	 BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY
**	FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW.  EXCEPT WHEN
**	OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES
**	PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER 
**	EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
**	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.  
**	THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM 
**	IS WITH YOU.  SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST 
**	OF ALL NECESSARY SERVICING, REPAIR OR CORRECTION.
**	
**	 IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING
**	WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MAY MODIFY, BE LIABLE
**	TO YOU FOR DAMAGES, INCLUDING ANY GENERAL, SPECIAL, INCIDENTAL OR 
**	CONSEQUENTIAL DAMAGES ARISING OUT OF THE USE OR INABILITY TO USE THE
**	PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS OF DATA OR DATA BEING RENDERED
**	INACCURATE OR LOSSES SUSTAINED BY YOU OR THIRD PARTIES OR A FAILURE OF
**	THE PROGRAM TO OPERATE WITH ANY OTHER PROGRAMS), EVEN IF SUCH HOLDER 
**	OR OTHER PARTY HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES
**
**	Any attempt otherwise to use, modify or distribute the Program is void, 
**	and will automatically terminate your rights under this License.
**	==========================================================================
**	
**
**	This program can be very usefull on hosts that do not allow regular (SSH) shell access.
**	However, there are a few things to keep in mind:
**
**		- it is web-based and potentially provides anyone with access with a 
**		*great deal* of information and access to the system internals.  In order to keep
**		your server's safe, you MUST correctly set and maintain the 'allowedIPs' configuration
**		directive.  You should consider uploading the program (e.g. through FTP) to your server
**		before each use, and removing it when you are done, each time in order to prevent
**		a stale configuration from giving an unauthorized user access.
**
**		- if you can, install and access the script through an SSL encrypted channel 
**		(https://www.example.com/phpsh.php)
**
**
**		- commands are run by the webserver and execute with its priveleges
**		This means you won't have all your regular rights (e.g. you can't write
**		files to certain directories, etc.) but you will have read permissions to
**		all files the webserver can serve up.
**		
**		- a number of configuration settings are available within the 
**		$PHPshConfig associative array. Read the comments.
*/

	$PHPshConfig = array(
	
				/* allowedIPs -- your first, and only, defense from baddies!
				**
				** The allowedIPs array specifies the list of remote addresses
				** the shell will accept commands from.
				**
				** Connections from unlisted IPs get a message indicating their
				** origin, so you can use that to set the IP as allowed and FTP
				** the modified version to the remote system.
				**
				** Example: array('127.0.0.1', '61.93.66.102'),
				** would allow connections only from localhost and 61.93.66.102.
				** There are no wildcards -- be specific and your system will be
				** relatively secure.
				**
				** Also, remove the script or at least the IP if you are using 
				** a shared address (such as through dialup) where someone else
				** will eventually be connected using the same I.P.
				*/
				'allowedIPs'	=> array(	$MyIPAddress,
								'127.0.0.1',),
				
				
				
				/* usefilecmd	set to TRUE to use `file` command rather than relying on
				**		mime magic to determine file content type when fetching.
				**		NOTE: This only works 
				**			- On Un*x systems
				**			- With 'commands' => 'file' set correctly, below.
				**
				*/
				'usefilecmd'		=> TRUE, // set to TRUE to use `file` command 
				
				
				// numhistory	number of previous commands to hold in history queue
				'numhistory'		=> 50,
				
				// use aliases to setup command aliases, e.g. 'lh -10' 
				// gets executed as 'ls -l | head -10'
				'aliases'	=> array(
				
							'ls'	=> 'ls -F',
							'lh'	=> 'ls -F -lth | head ',
							'fgr'	=> 'find . -type f | xargs -n 100 grep ',
							'psa'	=> 'ps waux',
						),
						
				/* disablecommands -- dissallow use of these commands.
				** This is EASY to get arround for anyone with the slightest bit of
				** experience, so these are mainly used as 'guidelines' or safeguards
				** for non-evil users.
				**
				** To thwart evil users, see the 'allowedIPs' above.
				*/
				'disabledcommands'	=> array('rm', 'ssh', 'passwd', 'su', 'ping', 'telnet'),
				
				
				
				'enableformatting'	=> TRUE,
				
				// formatcommandoutput	commands on which to post-process/format, if 
				//			enableformatting is TRUE.
				'formatcommandoutput'	=> array(
									'ls' 	=> TRUE,
									'find' 	=> TRUE,
									'ps'	=> TRUE,
									'psa'	=> TRUE,
									),
									
				// commands		Path to filesystem executables used
				'commands'		=> array(
								'hostname'		=> '/bin/hostname',
								'file'			=> '/usr/bin/file',
								),
						
				
				// formatting		Search and replace regexes used for formatting.
				'formatting'		=> array(
								'/(\s+|^)([^\s:\'<\[]+)\*/'
										=> '\1<span class="exec">\2</span>',
								'/(\s+|^)([^\s:\'<\[]+)\@/'
										=> '\1<span class="symlink">\2</span>',
								'/(\s+|^)([^\s:\'<\[]+)\/([\w\d\.]+)?/'
										=> '\1<span class="dir">\2/\3</span>',
								'/([^="\'])?(http(s?):\/\/[\w\d\/=\?\.\-]+)/'
										=> '\1<span class="url">\2</span>',
								'/\[([^\]]+)\]/'
										=> '[<span class="array">\1</span>]',
														),
				
				// maxexecseconds	-- *Should* kill programs that exec too long.
				//			Non-functional, so don't use interactive commands
				//			that hang.
				'maxexecseconds'	=> 45, // not really functional... see TODO:FIXME below...
				
				
				'promptfulldirpath'	=> FALSE, // set to TRUE to include full path in prompt
				
				// sesskeys		keys used in session, leave 'em alone.
				'sesskeys'		=> array(
				
								'commandhistory'	=> '_cmdhist',
								'pwd'			=> '_pwd',
								'lastwd'		=> '_lwd',
								'hostname'		=> '_hname',
								'escape'		=> '_esc',
								),
				
				
				'sessname'		=> 'phpshell',
				
				'nooutputescape'	=> FALSE, // set to TRUE to avoid escaping by default
				
				
				
				
			);
			
$PHPshVersion = '1.0.1';

	
	class PHPsh {
	
		var $pwd, $lastwd, $homedir, $hostname;
		
		function PHPsh ()
		{
			global $PHPshConfig;
			
			$this->init();
			
		}
		
		function init ()
		{
			global $PHPshConfig;
			session_name($PHPshConfig['sessname']);
			session_start();
			
			$sess =& $this->getSession();
			
			$this->homedir = dirname(__FILE__);
			if (array_key_exists($PHPshConfig['sesskeys']['pwd'], $sess))
			{
				$this->pwd = $sess[$PHPshConfig['sesskeys']['pwd']];
				if (array_key_exists($PHPshConfig['sesskeys']['lastwd'], $sess))
					$this->lastwd = $sess[$PHPshConfig['sesskeys']['lastwd']];
				else 
					$this->lastwd = $this->pwd;
					
			} else {
				$this->pwd = $this->homedir;
				$this->lastwd = $this->homedir;
			}
			
			
			
			if (array_key_exists($PHPshConfig['sesskeys']['hostname'], $sess))
			{
				$this->hostname = $sess[$PHPshConfig['sesskeys']['hostname']];
			} else {
				
				if (  file_exists($PHPshConfig['commands']['hostname']) )
				{
					$this->hostname = 
					   preg_replace('/\s*/', '', $this->popenAndReadCommand(
									$PHPshConfig['commands']['hostname']));
				} else {
					if (is_array($_SERVER) && array_key_exists('SERVER_NAME', $_SERVER))
						$this->hostname = $_SERVER['SERVER_NAME'];
					else
						$this->hostname = 'host';
						
					
				}
				
				$sess[$PHPshConfig['sesskeys']['hostname']] = $this->hostname;
			}
			
			if (array_key_exists($PHPshConfig['sesskeys']['hostname'], $sess))
			{
				// TODO
			}
			
			return ;
			
		} // end method init
		
		function & getSession ()
		{
			return $_SESSION;
		}
		
		function currentDir ()
		{
			return $this->pwd;
		}
		
		function moveToCurrentDir ()
		{
			
			chdir($this->pwd);
		}
		
		function getPrompt ()
		{
			global $PHPshConfig;
			$dir = $this->currentDir();
			if (! $PHPshConfig['promptfulldirpath'])
			{
				if (preg_match('/[^\/]/', $dir))
				{
					$dir = preg_replace('/(.+)\/([^\/]+)$/', '\2', $dir);
				}
			} 
			
			return '[' . $this->hostname . "&nbsp;$dir]&nbsp;\$ ";
		}
		
		
		
		function execCommand ($cmd, $escapeOutput=TRUE)
		{
			global $PHPshConfig;
			
			if (! empty ($cmd) )
			{
				$this->historyPush($cmd);
				$matches = array();
				if (preg_match('/^\s*cd\s+(.*)/', $cmd, $matches))
				{
					
					$this->changeDir($matches[1]);
				} else {
				
					$contents = NULL;
					if ($escapeOutput)
					{
						$contents = htmlentities($this->popenAndReadCommand($cmd));
						
					} else {
						$contents = $this->popenAndReadCommand($cmd);
					}
					
					
					if ($PHPshConfig['enableformatting'])
					{
						$matches = array();
						if (preg_match('/^\s*([^\s]+)/', $cmd, $matches))
						{
							if (array_key_exists($matches[1],
								 $PHPshConfig['formatcommandoutput']))
							{
						
								foreach ($PHPshConfig['formatting'] 
										as $search => $replace)
								{
									$contents = 
										preg_replace($search, $replace, $contents);
								}
								
							} // end if we format the output of this command
							
						} // end if we could extract this command from string
						
					} // end if formatting is even enabled
					
					print $contents;
					
				} // end if this is a simple cd to another dir
			} // end if we have a command
			
			return;
			
		}
		
		
		function popenAndReadCommand ($cmd)
		{
			global $PHPshConfig;
			
			if (empty($cmd))
				return "";
				
			$this->moveToCurrentDir();
			
			if (! preg_match('/>/', $cmd))
			{
				$cmd .= ' 2>&1';
			}

			
			
			$matches = array();
			if (preg_match('/^\s*([^\s]+)/', $cmd, $matches))
			{
				if (array_key_exists($matches[1], $PHPshConfig['aliases']))
				{
					$cmd = preg_replace('/^\s*' . $matches[1] . '/', 
							$PHPshConfig['aliases'][$matches[1]], $cmd);
				}
			}
			
			foreach ($PHPshConfig['disabledcommands'] as $badCommand)
			{
				if (preg_match('/(^\s*|;\s*|`\s*)' . $badCommand . '/', $cmd))
				{
					return "$badCommand is disabled";
				}
			}
			
				
			
			$contents = '';
			
			$startTime = time();
			$handle = popen($cmd, 'r');
			if ($handle)
			{
				$timeout = FALSE;
				
				/* TODO:FIXME maxexecseconds
				** It has to date been impossible to implement a clean
				** and cross-platform method of cancelling processes that
				** hang or run indefinitely (e.g. launching an interactive
				** 'vi' session, or 'ping hostname.example.com' on a linux box).
				**
				** The timeout code below forces the pipe closed but this can
				** leave the process running in the background nonetheless... meaning
				** you can end up with a server running 20 instances of vi as 'nobody'...
				**
				** Not so great.  For the moment, this is a TODO... likely infinite
				** commands can be barred using the 'disabledcommands' array.
				*/
				while (! ($timeout || feof($handle)) )
				{
					$timeSpent = time() - $startTime;
					if ($timeSpent >= $PHPshConfig['maxexecseconds'])
					{
						$timeout = TRUE;
						$contents .= "\nTimed out after $timeSpent seconds..."
							. "\nSet 'maxexecseconds' to change this.\n";
						
						flush();
						pclose($handle);
					} else {
						
						$contents .= fread($handle, 128);
					}
					
				}
				
				if (! $timeout)
					pclose($handle);
				
				
			} else {
				$contents = "Could not open handle for command '$cmd'\n";
			}
			
			
			
			
			
			return $contents;
		}
		
		function changeDir ($dir)
		{
			global $PHPshConfig;
			
			if ($dir == '~')
			{
				$dir = $this->homedir;
			} else if ($dir == '-')
			{
				$dir = $this->lastwd;
			}
			
			$lastDir = $this->pwd;
			chdir($lastDir);
			
			if (! file_exists($dir))
			{
				print "cd: $dir: No such file or directory<br />\n";
				return;
			}
			
			if (! @chdir($dir) )
			{
				print "cd $dir: Failed<br />\n";
				return;
			}
			
			$curDir = getcwd();
			$this->pwd = $curDir;
			
			$sess =& $this->getSession();
			$sess[$PHPshConfig['sesskeys']['pwd']] = $curDir;
			$sess[$PHPshConfig['sesskeys']['lastwd']] = $lastDir;
			
		} // end method changeDir
		
		function historyPush ($cmd)
		{
			
			global $PHPshConfig;
			$sess =& $this->getSession();
			
			/* make sure we have a command history array */
			if (! (array_key_exists($PHPshConfig['sesskeys']['commandhistory'], $sess)
				&& is_array($sess[$PHPshConfig['sesskeys']['commandhistory']])))
				$sess[$PHPshConfig['sesskeys']['commandhistory']] = array();
				
			
			/* make sure the command history doesn't grow too large */
			if (count($sess[$PHPshConfig['sesskeys']['commandhistory']]) 
				>= $PHPshConfig['numhistory'])
			{
				// too large, shrink it to max size - 1
				$shrunkenArray = array_slice($sess[$PHPshConfig['sesskeys']['commandhistory']],
								0, $PHPshConfig['numhistory'] - 1);
				
				$sess[$PHPshConfig['sesskeys']['commandhistory']] = $shrunkenArray;
			}
			
			
			/* add this command to the front of the array */	
			if (
				(! count($sess[$PHPshConfig['sesskeys']['commandhistory']]))
				||
				($cmd != $sess[$PHPshConfig['sesskeys']['commandhistory']][0])
			)
				array_unshift($sess[$PHPshConfig['sesskeys']['commandhistory']], $cmd);
			
			return;
			
		} // end method historyPush
		
		
		function getHistoryOptions ()
		{
		
			global $PHPshConfig;
			$sess =& $this->getSession();
			
			
			$retArray = array();
			
			if (! (array_key_exists($PHPshConfig['sesskeys']['commandhistory'], $sess)
				&& is_array($sess[$PHPshConfig['sesskeys']['commandhistory']])))
				
				return $retArray;
				
			foreach ($sess[$PHPshConfig['sesskeys']['commandhistory']] as $cmd)
			{
				$escapedCmd = htmlentities($cmd);
				array_push($retArray,
					'<option value="' . $escapedCmd . "\">$escapedCmd</option>");
			}
			
			return $retArray;
		}
		
		function showFile ($fname, $escapeOutput=TRUE)
		{
			global $PHPshConfig;
			
			$fullpath = $this->currentDir() . "/$fname";
			if (!  is_readable($fullpath))
			{
				print "Unable to read $fullpath";
				return;
			}
			
			$ctype = 'text/plain';
			if ($PHPshConfig['usefilecmd'] && is_readable($PHPshConfig['commands']['file']))
			{
				$f = escapeshellarg($fullpath);
				$cmd = $PHPshConfig['commands']['file'] . " -bi $f";
				$ctype = trim( `$cmd` );
				
				$ctype = preg_replace('/[,\s].*$/', '', $ctype);// sometimes file includes
										// weirdness, chop it off.
				
       			} else if (function_exists('mime_content_type'))
			{
			
				$ctype = mime_content_type($fullpath) ;
			}
			
			if ($ctype == 'text/html' && $escapeOutput)
			{
				// it's html and we do want to escape...
				// send it as plain text
				header("Content-type: text/plain\r\n\r\n");
			} else {
				header( "Content-type: $ctype\r\n\r\n");
			}
			
			readfile( $fullpath);
			
			
			return;
		}
			
			
		
		
	} // end PHPsh class definition
	
	
	/* Oh, how I loathe 'magic' !! */
	/* P.S. this request to disable the magic
	** doesn't seem to work very well...
	** code above uses stripslashes if its still on */
	set_magic_quotes_runtime(0);
	ini_set('magic_quotes_gpc', 0);
	
	
	/* Important security check!! */
	$allowAccess = FALSE;
	if (array_key_exists('REMOTE_ADDR', $_SERVER))
	{
		foreach($PHPshConfig['allowedIPs'] as $ip)
		{
			if ($ip == $_SERVER['REMOTE_ADDR'])
				$allowAccess = TRUE;
		}
	}
	
	
	$shell = new PHPsh;
	
	


	
	$CurDir = $shell->currentDir();
	$Command = NULL;
	$OutputEscapeFlag = TRUE;
	$MySess =& $shell->getSession();
	if (array_key_exists($PHPshConfig['sesskeys']['escape'], $MySess))
	{
		$OutputEscapeFlag = $MySess[$PHPshConfig['sesskeys']['escape']];
	}
	
	/* Take care of getfile requests right away, using the escape flag from session */
	if ($allowAccess)
	{
		if (is_array($_GET) )
		{
			if (array_key_exists('getfile', $_GET))
			{
				$shell->showFile($_GET['getfile'], $OutputEscapeFlag);
				exit(1);
			}
		}
		
	}
	
			
	$HaveUpload = FALSE;
	if (is_array($_FILES) && array_key_exists('uploadfile', $_FILES)
		&& is_array($_FILES['uploadfile'])
		&& array_key_exists('name', $_FILES['uploadfile'])
		&& strlen($_FILES['uploadfile']['name'])
		&& array_key_exists('tmp_name', $_FILES['uploadfile'])
		&& $_FILES['uploadfile']['tmp_name'])
	{
		$HaveUpload = TRUE;
	}
	
	
	
	if (is_array($_GET) && array_key_exists('command', $_GET))
	{
		$Command = $_GET['command'];
		
		if (array_key_exists('escapeoutput', $_GET))
			$OutputEscapeFlag = $_GET['escapeoutput'];
	}
	
	if (is_array($_POST))
	{
	
		
		if (array_key_exists('command', $_POST) && $_POST['command'])
			$Command = $_POST['command'];
		else if (array_key_exists('prevcommand', $_POST) 
				&& $_POST['prevcommand'])
			$Command = $_POST['prevcommand'];
		
		
		
		if (array_key_exists('escapeoutput', $_POST))
		{
			$OutputEscapeFlag = $_POST['escapeoutput'];
		}
		
		if (get_magic_quotes_gpc())
		{
			$Command = stripslashes($Command);
		}
			
	}
	
	if ($Command && preg_match('/^\s*cd\s+/', $Command))
	{
		$CurDir = ""; // will change, don't print.
	}
	
	
	$MySess[$PHPshConfig['sesskeys']['escape']] = $OutputEscapeFlag;
	
	
?>
