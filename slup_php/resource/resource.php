<?php
/**
 *common resources to be used in applications.
 *It can be rewritten freely value.
 * アプリケーションで共通で使用するリソース
 *値は自由に書き換えて良い
 */
interface CommonResources{
	const year  = "年";
	const month  = "月";
	const date  = "日";
	const hour  = "時";
	const minute = "分";
	const second  = "秒";
	
	/**
	 * image
	 */
	const outputImageTitle="タイトル";
	
	/**
	 * message
	 */
	const dataRegist="登録";
	const dataRegistSuccess="登録されました！";
	const notDateRegist="まだ登録がありません。";
	const dataUpdate="更新";
	const dataUpdateSuccess="更新しました！";
	const dataDelete="削除";
	const dataDeleteSuccess="削除しました。";
	const noneSpecified="指定なし";
	const dataNext="次へ";
	const dataSearch="Search";
	
	/**
	 * error message
	 */
	const errorMessageBadDateTime="存在しない日時です。";
	const securityErrorMessage="security error.";
	const requireErrorMessage="は必須項目です。";
	const validationErrorNumeric="は数字として正しくありません。";
	const validationErrorInteger="は数字ではありません。";
	const validationErrorCtypeAlnum="が英数字ではありません。";
	const validationErrorAlnum="が半角英字ではありません。";
	const validationErrorCtypeAlnum_bar="に半角英数字、アンダーバー、ハイフン以外の文字が入力されています。";
	const validationErrorMailAdd="の値として正しくありません。";
	const validationErrorUrl="はURLとして正しくありません。";
	const validationEquals="に不正な値が送信されました。";
	
	const imageWrongError="不正な画像ファイルです。";
	const imageSizeError="画像サイズオーバーです。";
	const imageTypeError="画像ファイルではありません。";
	
	const requireInput="※必須入力";
	
	const nullCharacter="";
	const slash="/";
	const quote="'";
	const equal="=";
	const space=" ";
	const db_space="　";
	const rightLess=">";
	const leftLess="<";
	const comma=",";
	const question="?";
	const period=".";
	const leftBrackets="(";
	const rightBrackets=")";
	const indention = "\r\n";
	const punctuation="｜";
	const big_colon="：";
	const asterisk="*";
	const underscore="_";
	
}
interface ModelResource{
	/**
	 * User
	 */
	const sl_user_id="ID";
	const sl_user_password="パスワード";
	const sl_user_m_id="メールアドレス";
	const sl_user_consentCheck="規約同意のチェック";
	const sl_user_passwordConfirmation="確認用パスワード";
	/**
	 * image
	 */
	const sl_image_id="画像ID";
	const sl_image_title="画像タイトル";
	const sl_image_size="画像サイズ";
	const sl_image_type="画像タイプ";
	const sl_image_user="ユーザー";
}
class ErrorMessage{
	/**
	 * if greater than the maximum value.
	 * 最大値より大きい数値の場合に出力されるエラーメッセージ
	 * @param int $max maximum value.
	 * @return string
	 */
	public static function getCheckNumMaxError($max){
		return "は".$max."以下の数値で入力して下さい。";
	}
	/**
	 *if less than the minimum.
	 * 最小値より小さい数値の場合に出力されるエラーメッセージ
	 * @param int $min
	 * @return string
	 */
	public static function getCheckNumMinError($min){
		return "は".$min."以上の数値で入力して下さい。";
	}
	/**
	 *if number of characters is not enough.
	 *文字数が少ない時に出力されるエラーメッセージ
	 * @param int $min_len
	 */
	public static function getCheckMinLength($min_len){
		return "は".$min_len."文字以上で入力してください。";
	}
	/**
	 *if number of characters is often
	 *文字数が多い時に出力されるエラーメッセージ
	 * @param int $max_len
	 */
	public static function getCheckMaxLength($max_len){
		return "は".$max_len."文字以下で入力してください。";
	}
	
	public static function getCheckDuplication($outputName){
		return "その".$outputName."は既に登録されています。";
	}
	
}
class Syntax implements CommonResources{
	public static function leftSpace($str){
		return self::space.$str;
	}
}
/**

class SqlSyntax implements CommonResources{
	
	const inner_join="inner join";
	const left_join="left join";
	const right_join="right join";
	const joinOn="on";
	const funcNow="now()";
	const asc="asc";
	const desc="desc";
	
	const isNull=" is null ";
	
	public static function getAnd(){
		return self::space.self::andSql.self::space;	
	}
	public static function getOr(){
		return self::space.self::orSql.self::space;
	}
	public static function getJoinOn(){
		return self::space.self::joinOn.self::space;
	}
	public static function getIn(){
		return self::space."in".self::space.self::leftBrackets;
	}
	public static function getNotIn(){
		return self::space."not".self::space."in".self::space; 
	}
	
	public static function getCurrentDate_where() {
		return "between current_date and DATE_FORMAT(current_date, '%Y-%m-%d 23:59:59' )";
	}
}
**/


?>