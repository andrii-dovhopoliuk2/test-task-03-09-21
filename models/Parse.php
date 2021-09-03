<?php


namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Parse extends ActiveRecord
{
    const FORMAT_XML = 10;
    const FORMAT_CSV = 20;
    const FORMAT_TXT = 30;

    public $db_name;

    /**
     * Parse constructor.
     * @param $db_name
     */
    public function __construct($db_name = false)
    {
        if (!is_dir(__DIR__ . "/../runtime/parsed/")) {
            mkdir(__DIR__ . "/../runtime/parsed/");
        }
        if ($db_name) {
            $this->db_name = $db_name;
        }
        parent::__construct();
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%parses}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            ['format', 'integer'],
            ['file', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @param $db_posts
     */
    public function parse($db_posts)
    {
        $text_item = '';
        $xml_item = '';
        foreach ($db_posts as $post) {
            $title = $this->clearText($post['post_title']);
            $text = $this->clearText($post['post_content']);
            $text_item .= "{$title};" . str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $text) . "\r\n";
            $xml_item .= "<Item><title>{$title}</title><text><![CDATA[{$text}]]></text></Item>";
        }
        $this->parseCsv($text_item);
        $this->parseTxt($text_item);
        $this->parseXml($xml_item);
    }

    /**
     * @return string[]
     */
    public static function getFormats()
    {
        return [
            self::FORMAT_XML => 'xml',
            self::FORMAT_TXT => 'txt',
            self::FORMAT_CSV => 'csv',
        ];
    }

    /**
     * @param $text
     */
    private function parseCsv($text)
    {
        $name_file = $this->writeFile('csv', $text);
        $this->selfSave($name_file, self::FORMAT_CSV);
    }

    /**
     * @param $text
     */
    private function parseTxt($text)
    {
        $name_file = $this->writeFile('txt', $text);
        $this->selfSave($name_file, self::FORMAT_TXT);
    }

    /**
     * @param $text_item
     */
    private function parseXml($text_item)
    {
        $text = '<?xml version="1.0" encoding="UTF-8"?><title name="posts">';
        $text .= $text_item;
        $text .= '</title>';
        $name_file = $this->writeFile('xml', $text);
        $this->selfSave($name_file, self::FORMAT_XML);
    }

    /**
     * @param $name_file
     * @param $format
     * @return bool
     */
    private function selfSave($name_file, $format)
    {
        $model = new self();
        $model->name = $name_file;
        $model->format = $format;
        $model->file = $name_file;
        return $model->save();
    }

    /**
     * @param $format
     * @param $text
     * @return string
     */
    public function writeFile($format, $text, $_name_file = false)
    {
        if (!is_dir(__DIR__ . "/../runtime/parsed/{$format}/")) {
            mkdir(__DIR__ . "/../runtime/parsed/{$format}/");
        }
        $name_file = $_name_file ? $_name_file : "{$this->db_name}_post_" . time() . ".$format";
        $fp = fopen(__DIR__ . "/../runtime/parsed/$format/{$name_file}", "w");
        fwrite($fp, $text);
        fclose($fp);
        return $name_file;
    }

    /**
     * clear html tags (<a></a><img>)
     * @param $text
     * @return string|string[]|null
     */
    private function clearText($text)
    {
        $pattern_href = '/<a([\s\S]+)?>([\s\S]+)?<\/a>/i';
        $pattern_img = '/<img[^>]+\>/i';
        $text = preg_replace($pattern_href, "", $text);
        $text = preg_replace($pattern_img, "", $text);

        return $text;
    }


}