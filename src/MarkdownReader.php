<?php

/**
MARKDOWN -> TEXT | SPECIAL_CHAR;
TEXT -> TEXT | SPECIAL_CHAR;
SPECIAL_CHAR-> SPECIAL_CHAR | TEXT;

SPECIAL_CHAR: #,`,*,[,>;
#: #{1+};
`:`TEXT{1+}`;
\*:*{1-2}TEXT{1+}*{1-2}
[: [TEXT](TEXT);
>:>TEXT{1+}
**/
include('Symbols.php');
include('Node.php');
include('Ast.php');

ini_set ( "xdebug.max_nesting_level" , "2147483647" );

class MarkdownReader
{


  private $raw_text;
  private $output;
  private $line_count;
  private $current_line;
  private $full_text;
  private $full_text_array;
  private $partial_node_text;

  private $ast;
  private $current_node;

  function __construct($source)
  {
    $this->line_count = 0;
    $this->current_line = "";
    $this->full_text = "";

    $this->ast = new Ast();
    $this->current_node = new Node();

    if(is_file($source))
    {
      $file = fopen($source, "r");

      while(!feof($file))
      {
        $line = fgets($file);
        $this->full_text .= $line;
      }

      fclose($file);
    }
    else if(is_string($source))
    {
      $this->full_text = $source;
    }
    $this->parse_r(array_reverse(str_split($this->full_text) ) );
  }

  function parse_r($text)
  {
    $text_array = $text;
    if(count($text_array) == 0)
    {
      return;
    }
    //pop off head char
    $char = $text_array[count($text_array) - 1];
    array_pop($text_array);

    $this->partial_node_text .= $this->analyze($char);

    $this->parse_r($text_array);

  }
  function analyze($char)
  {
    if(strpos($char,"#") === 0)
    {
      echo Symbols::HASH . PHP_EOL . "<br>";
    }
    else if(strpos($char,"`") === 0)
    {
      echo Symbols::BACK_TICK . PHP_EOL. "<br>";
    }
    else if(strpos($char,"*") === 0)
    {
      echo Symbols::ASTERIX . PHP_EOL. "<br>";
    }
    else if(strpos($char,"[") === 0)
    {
      echo Symbols::SQUARE_BRACE_L . PHP_EOL. "<br>";
    }
    else if(strpos($char,">") === 0)
    {
      echo Symbols::POINTY_BRACE_R . PHP_EOL. "<br>";
    }
    else
    {
      echo Symbols::TEXT;
      $this->current_node->setNodeType(Symbols::TEXT);
    }
  }

  function analyzeText($text)
  {
    $this->current_line = $text;
    //Split up characters
    if(!empty($this->current_line))
    {
      $chars = str_split($this->current_line);
      echo ": ";
      foreach($chars as $key => $value)
      {
        if($value == str_split(Symbols::BACK_TICK)[0])
        {
          echo Symbols::BACK_TICK;
        }
        else if($value == str_split(Symbols::ASTERIX)[0])
        {
          echo Symbols::ASTERIX;
        }
        else if($value == str_split(Symbols::SQUARE_BRACE_L)[0])
        {
          $this->expect(Symbols::TEXT);
          $this->expect(Symbols::SQUARE_BRACE_R);
          $this->expect(Symbols::BRACE_L);
          $this->expect(Symbols::TEXT);
          $this->expect(Symbols::BRACE_R);
        }
        else if($value == str_split(Symbols::POINTY_BRACE_R)[0])
        {
          echo Symbols::POINTY_BRACE_R;
        }
      }
      echo "<br>";
    }
  }

  function expect($sym)
  {

    return true;
  }
  function parse($line)
  {
    $this->line_count++;
    //Parse basic # tags
    if(gettype(strpos($line,"#")) == "integer")
    {
      $line = str_replace("#","",$line);
      $line = "<h1>" . $line . "</h1>";
    }



    return $line;
  }

}
?>
