<?php

namespace WMDE\ConfigDiff;

class ConstantParser {

	const S_OTHER = 0;
	const S_DEFINE = 1;
	const S_KEY = 2;
	const S_VALUE = 3;

	private $state;
	private $currentKey;
	private $currentValue;
	private $constants;

	public function getConstants( $source ) {
		$tokens = token_get_all( $source );
		$this->constants = [];
		$this->state = self::S_OTHER;
		while ( $token = next( $tokens ) ) {
			$this->consumeToken( $token );
		}
		return $this->constants;
	}

	private function consumeToken( $token ) {
		switch ( $this->state ) {
			case self::S_OTHER:
			if ( $this->isDefineStatement( $token ) ) {
				$this->state = self::S_DEFINE;
			}
			return;
			case self::S_DEFINE:
			if ( $token == '(' ) {
				$this->state = self::S_KEY;
				$this->currentKey = '';
			}
			return;
			case self::S_KEY:
			if ( $token == "," ) {
				$this->state = self::S_VALUE;
				$this->currentValue = '';
				return;
			}
			$this->currentKey .= $this->getTokenContents( $token );
			return;
			case self::S_VALUE:
			if ( $token == ")" ) {
				$this->state = self::S_OTHER;
				$this->constants[$this->currentKey] = $this->currentValue;
				return;
			}
			$this->currentValue .= $this->getTokenContents( $token );
			return;
		}
	}

	private function getTokenContents( $token ) {
		if ( !is_array( $token ) ) {
			return $token;
		}
		if ( $token[0] == T_WHITESPACE ) {
			return '';
		}
		return $token[1];
	}

	private function isDefineStatement( $token ) {
		return is_array( $token ) && $token[0] == T_STRING && $token[1] == 'define';
	}

}
