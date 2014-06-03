<?php
	interface BuilderResources{
		/**
		 * tab character
		 * @var string
		 */
		const tab  = "\t";
		/**
		 * @var string
		 */
		const leftBrace = "{";
		/**
		 * @var string
		 */
		const rightBrace = "}";
		/**
		 * @var string
		 */
		const colon = ":";
		/**
		 * @var String
		 */
		const semicolon  = ";";
		/**
		 * @var String
		 */
		const doubleQuote  = "\"";
		/**
		 * @var String
		 */
		const asterisk = "*";
		/**
		 * @var String
		 */
		const leftBrackets = "[";
		/**
		 * @var String
		 */
		const rightBrackets  = "]";
		/**
		 * @var String
		 */
		const leftInequalitySign = "<";
		/**
		 * @var String
		 */
		const rightInequalitySign  = ">";
		/**
		 * @var String
		 */
		const underscore  = "_";
		
		
		/**
		 * class.
		 */
		/**
		 * string for build the class.
		 */
		/**
		 * @var string
		 */
		const classString = "class";
		/**
		 * @var string
		 */
		const interfaceString  = "interface";
		/**
		 * @var string
		 */
		const extendsString = "extends";
		/**
		 * @var string
		 */
		const implementsString  = "implements";
		/**
		 * @var string
		 */
		const privateString="private";
		/**
		 * @var String
		 */
		const publicString="public";
		/**
		 * @var String
		 */
		const protectedString="protected";
		/**
		 * @var String
		 */
		const functionString="function";
		/**
		 * @var String
		 */
		const abstractString = "abstract";
		/**
		 * @var String
		 */
		const getterString  = "get";
		/**
		 * @var String
		 */
		const setterString  = "set";
		/**
		 * @var string
		 */
		const createString = "create";
			
		/**
		 * @var String
		 */
		const returnString  = "return";
		/**
		 * @var String
		 */
		const newStr  = "new";
		/**
		 * @var String
		 */
		const arrayStr = "array";
		
		const dollar="$";
		/**
		 * php
		 */
		/**
		 * firstString
		 * 先頭文字列
		 * @var string
		 */
		const firstString  = "<?php";
		/**
		 * @var string
		 */
		const thisString  = "\$this->";
		/**
		 * @var string
		 */
		const parentString  = "parent";
		/**
		 * @var string
		 */
		const arrowStr = "->";
		
		const insertQuart="\"";
	}
	class CustomTag implements BuilderResources{
		const idIndex="id";
		public static function baseCustomTag($tagName,$add=""){
			return self::leftBrackets.$tagName.CommonResources::space.$add.CommonResources::space.self::rightBrackets;
		}
		public static function idCustomTag($tagName,$id,$add=""){
			return self::baseCustomTag($tagName,self::idIndex.CommonResources::equal.self::getEncloseValue().$id.self::getEncloseValue().$add);
		}
		public static function replaceCustomTag($str,$tagName,$list=array()){
			$list[self::getEncloseValue()]=self::insertQuart;
			$list[self::rightBrackets]=self::rightInequalitySign;
			$list[self::leftBrackets.$tagName]=self::leftInequalitySign.$tagName;
		//	print_r($list);
			return strtr($str,$list);
		}
		public static function replaceIdCustomTag($str,$tagName,$replaseId,$list=array()){
			$list[self::idIndex.CommonResources::equal.self::getEncloseValue()]=$replaseId;
			return self::replaceCustomTag($str,$tagName,$list);
		}
		public static function replaceToTag($str,$tagName,$list=array()){
			$list[self::insertQuart]=self::getEncloseValue();
			$list[self::rightInequalitySign]=self::rightBrackets;
			$list[self::leftInequalitySign.$tagName]=self::leftBrackets.$tagName;
			//	print_r($list);
			return strtr($str,$list);
		}
		public static function replaceToIdTag($str,$tagName,$replaseId,$list=array()){
			$list[$replaseId]=self::idIndex.CommonResources::equal.self::getEncloseValue();
			return self::replaceToTag($str, $tagName,$list);
		}
		public static function getEncloseValue(){
			return self::underscore.self::semicolon;
		}
		
	}
?>