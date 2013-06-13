<?php

	function kill( $data ) { die( var_dump ( $data ) ); }
	function killArray( $data ) {
		foreach ($data as $key){
			foreach ($key as $value) {
				die( var_dump ( $value ) );
			}
		}
	}
?>