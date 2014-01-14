<?php
 
class MY_Email extends CI_Email
{
    /**
     * Set Email Subject
     *
     * @access    public
     * @param     string
     * @return    void
     */
    function subject($subject)
    {
        $subject = '=?'. $this->charset .'?B?'. base64_encode($subject) .'?=';
        $this->_set_header('Subject', $subject);
    }

	public function from($from, $name = '')
	{
		if (preg_match( '/\<(.*)\>/', $from, $match))
		{
			$from = $match['1'];
		}

		if ($this->validate)
		{
			$this->validate_email($this->_str_to_array($from));
		}

		// prepare the display name
		if ($name != '')
		{
			// only use Q encoding if there are characters that would require it
			if ( ! preg_match('/[\200-\377]/', $name))
			{
				// add slashes for non-printing characters, slashes, and double quotes, and surround it in double quotes
				$name = '"'.addcslashes($name, "\0..\37\177'\"\\").'"';
			}
			else
			{
				$name = '=?'. $this->charset .'?B?'. base64_encode($name) .'?=';
			}
		}

		$this->_set_header('From', $name.' <'.$from.'>');
		$this->_set_header('Return-Path', '<'.$from.'>');

		return $this;
	}
}