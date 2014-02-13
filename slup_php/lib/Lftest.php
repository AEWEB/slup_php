<?php
	/**
	 * Interface to the test case.
	 * テストケースへのインタフェース
	 */
	interface Lf_testCaseRunnable{
		/**
		 * To execute when object is created. 
		 * オブジェクト作成時に実行される
		 * @return void
		 */
		public function create();
		/**
		 * To Execute at the end of the test.
		 * テスト終了時に実行される
		 * @return void
		 */
		public function exitTest();
	}
	/**
	 * Interface to the unit test class.
	 *単体テストクラスへのインタフェース
	 */
	interface Lf_testRunnable{
		/**
		 * Check for equal.
		 * 値が等しいかチェック
		 * @param string $str value.値
		 * @param string $correct　Value for objective.目的の値
		 * @return void
		 */
		public function equals($str,$correct) ;
		/**
		 * Check for null.
		 * nullがどうかチェック
		 * @param mixed $object
		 * @return void
		 */
		public function equalsNull($object);
		/**
		 * @param mixed $object
		 */
		public function equalsNotNull($object);
		/**
		 * Check for true.
		 * 真かどうかチェック
		 * @param bool $bool
		 * @return void
		 */
		public function equalsTrue($bool);
		/**
		 * Check for it's same object.
		 * 同じオブジェクトを参照しているかチェック
		 * @param mixed $obj
		 * @param mixed $obj2
		 * @return void
		 */
		public function equalsObj($obj,$obj2);
		/**
		 * Output info.
		 * 情報を出力する
		 * @return void
		 */
		public function outputInfo();
	}
	/**
	 * Class of tastCase. 
	 * テストケースのクラス
	 */
	abstract class Lf_testCase implements Lf_testCaseRunnable{
		/** 
		 * @var Lf_testRunnable controler
		 */
		private $control;
		/**
		 * Constructor
		 * コンストラクタ
		 * @param Lf_testRunnable $control
		 */
		public function Lf_testCase($control){
			$this->control=$control;
		}
		/**
		 * implements Lf_testCaseRunnable
		 */
			/**
			 * To execute when object is created.
			 * オブジェクト作成時に実行される
			 * @return void
			 */
			public function create(){}
			/**
			 * To Execute at the end of the test.
			 * テスト終了時に実行される
			 * @return void
			 */
			public function exitTest(){}
	 
		/**
		 * Get the controler.
		 * @return Lf_testRunnable
		 */
		protected function getControl() {
			return $this->control;
		}
	 
	}
	
   /**
    *Class for unit test.
    *単体テストクラスへのインタフェース
    */
   class LF_test{
   		/**
   		 * List for class to test.
   		 * テストするクラスのリスト
   		 * @var string[]
   		 */
   		private $testClassList;
   		/**
   		 * List for storing the output information.
   		 * 出力情報を格納するためのリスト
   		 * @var string[]
   		 */
   		private $outputList=array();
   		/**
   		 * First character for method to be test. 
   		 * テスト対象となるメソッドの先頭文字
   		 * @var string
   		 */
   		const firstCharacter="/^test/";
   		
   		/**
   		 * Constructor
   		 * コンストラクタ
   		 * @param string[] $testClassList List for class to test.テストするクラスのリスト
   		 */
   		public function LF_test($testClassList){
   			$this->testClassList=$testClassList;
   			$this->init();   			
   		}
   		/**
   		 * Initialization.
   		 * 初期化処理
   		 * @return void
   		 */
   		protected function init() {
   			$list=$this->getTestClassList();
   			for($i=0;$i<count($list);$i++){
   				require_once AppConfigTest::getTestCasePath().($run_test=$list[$i].AppConfigTest::testClassIndex).".php";
   				$this->run(new $run_test($this));
   			}
   		}
   		/**
   		 * Get list for test case.
   		 * テストケースのリストを取得。
   		 * @return string[]
   		 */
   		protected function getTestClassList(){
   			return $this->testClassList;
   		}
   		/**
   		 * Run the test. 
   		 * テストを実行　
   		 * @param Lf_testRunnable $testCase
   		 * @return void
   		 */
   		protected function run($testCase){
   			$testCase->create();
   			$methods=get_class_methods($testCase);
   			for($i=0;$i<count($methods);$i++){
   				if(preg_match(self::firstCharacter,$methods[$i])){//"test"が頭に含まれているか
   					call_user_func_array(array($testCase,$methods[$i]),array());//テストメソッドを実行
   				}
   			}
   			$testCase->exitTest();
   		}
   		/**
   		 * Output error.
   		 * エラー出力
   		 * @param mixed $str Error target.エラー対象
   		 * @param String[] $trace0 0th array of it's got from debug_backtrace. debug_backtraceから得た配列
   		 * @param String[] $trace1 1th array of get from debug_backtrace. debug_backtraceから得た配列
   		 */
   		protected function outputError($str,$trace0,$trace1) {
   			$this->outputInfo();
   			print("------------------------------------------error!!!------------------------------------------<br/>".
   				$str."___value<br/>".
   				$trace0["file"]."___file<br/>".
   				$trace0["line"]."___line<br/>".
   				$trace1["class"]."___class<br/>".
   				$trace1["function"]."___method<br/>" );
   			exit();
   		}
   		/**
   		 * Success output.
   		 * 成功時の出力
   		 * @param mixed $str Success value.
   		 * @param string[] $trace1 1th array of get from debug_backtrace. debug_backtraceから得た配列
   		 */
   		protected function addOutputList($str,$trace1) {
   			$this->outputList[]="------------------------------------------success!!!------------------------------------------<br/>".
   				$str."___value||||||||||||||||".
   				$trace1["function"]."___method<br/>";
   		}
   		/**
   		 * Get output list.
   		 * 出力用のリストを取得
   		 * @return string[]
   		 */
   		protected function getOutputList(){
   			return $this->outputList;
   		}
   		
   		/**
   		 * implements Lf_testRunnable
   		 */
   			/**
			 * Check for equal.
			 * 値が等しいかチェック
			 * @param string $str value.値
			 * @param string $correct　Value for objective.目的の値
			 * @return void
			 */
   			public function equals($str,$correct){//Check for equal. 等しいかどうかを確認します。
   				if($str!==$correct){//If the value is incorrect. 値が正しくない場合
   					$backtraces = debug_backtrace();
   					$this->outputError($str."___str___".$correct."___correct___",
   						$backtraces[0],$backtraces[1]);
   				}
   				$backtraces = debug_backtrace();
   				$this->addOutputList($str,$backtraces[1]);
   			}
   			/**
   			 * Check for null.
   			 * nullがどうかチェック
   			 * @param mixed $object
   			 * @return void
   			 */
   			public function equalsNull($object){//Check for null そのオブジェクトがnullがどうか調べる
   				if($object!==null){//If the value is incorrect. 値が正しくない場合
   					$backtraces = debug_backtrace();
   					$this->outputError("Object is not NULL",
   						$backtraces[0],$backtraces[1]);
   				}
   				$backtraces = debug_backtrace();
   				$this->addOutputList("Object NULL!!!",$backtraces[1]);
   			}
   			/**
   			 * @param mixed $object
   			 */
   			public function equalsNotNull($object){
   				if($object===null){//If the value is incorrect. 値が正しくない場合
   					$backtraces = debug_backtrace();
   					$this->outputError("Object is NULL",
   							$backtraces[0],$backtraces[1]);
   				}
   				$backtraces = debug_backtrace();
   				$this->addOutputList("Object not NULL!!!",$backtraces[1]);
   			}
   			
   			/**
   			 * Check for true.
   			 * 真かどうかチェック
   			 * @param bool $bool
   			 * @return void
   			 */
   			public function equalsTrue($bool){//Check for true 真がどうか調べる
   				if(!$bool){//If false.
   					$backtraces = debug_backtrace();
   					$this->outputError("false",
   					$backtraces[0],$backtraces[1]);
   				}
   				$backtraces = debug_backtrace();
   				$this->addOutputList("true!!!",$backtraces[1]);
   			}
   			/**
   			 * Check for it's same object.
   			 * 同じオブジェクトを参照しているかチェック
   			 * @param mixed $obj
   			 * @param mixed $obj2
   			 * @return void
   			 */
   			public function equalsObj($obj,$obj2){//to check that are same object. 同じオブジェクトを参照しているかチェック
   			
   				if($obj!==$obj2){//If the value is incorrect. 値が正しくない場合
   					$backtraces = debug_backtrace();
   					$this->outputError("Object false",
   						$backtraces[0],$backtraces[1]);
   				}
   				$backtraces = debug_backtrace();
   				$this->addOutputList("Object True",$backtraces[1]);
   			}
   			/**
   			 * Output info.
   			 * 情報を出力する
   			 * @return void
   			 */
   			public function outputInfo(){//output info.情報を出力
   				$list=$this->getOutputList();
   				for($i=0;$i<count($list);$i++){
   					print($list[$i]);
   				}
   			}
   		
   	}
   	
?>