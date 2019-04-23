<?php 

if($User->logged()) Redirect::to('home');

list($userid,$hash) = explode('-',$_subsection,2);