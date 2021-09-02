<?php


namespace app\models;


use yii\base\Model;

class JoinParse extends Model
{
    public $join_files;
    public $format;

    /**
     * JoinParse constructor.
     * @param $format
     */
    public function __construct($format)
    {
        $this->format = $format;
        parent::__construct();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['join_files'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @return bool
     */
    public function joinFileParse()
    {
        $parses = Parse::find()->where(['id' => $this->join_files])->all();
        switch ($this->format) {
            case Parse::FORMAT_XML:
                $this->joinXml($parses);
                break;
            case Parse::FORMAT_TXT || Parse::FORMAT_CSV:
                $this->joinTxt($parses);
                break;
        }

        return true;
    }

    /**
     * @param $parses
     */
    private function joinTxt($parses)
    {
        $text = '';
        foreach ($parses as $parse) {
            $path = __DIR__ . "/../runtime/parsed/" . Parse::getFormats()[$parse->format] . "/{$parse->file}";
            if (is_file($path)) {
                $text .= file_get_contents($path);
            }
        }
        $this->saveJoin($text);
    }

    /**
     * @param $parses
     */
    private function joinXml($parses)
    {
        $xmls = [];
        foreach ($parses as $parse) {
            $path = __DIR__ . "/../runtime/parsed/" . Parse::getFormats()[$parse->format] . "/{$parse->file}";
            if (is_file($path)) {
                $xmls[] = simplexml_load_file($path, null, LIBXML_NOCDATA);

            }
        }
        $text_item = '';
        foreach ($xmls as $xml) {
            foreach ($xml->Item as $item) {
                $text_item .= "<Item><title>{$item->title}</title><text><![CDATA[{$item->text}]]></text></Item>";
            }
        }
        $text = '<?xml version="1.0" encoding="UTF-8"?><title name="posts">';
        $text .= $text_item;
        $text .= '</title>';
        $this->saveJoin($text);
    }

    /**
     * @param $text
     */
    private function saveJoin($text)
    {
        $parse_new = new Parse();
        $parse_new->name = 'joined_' . time() . '.' . Parse::getFormats()[$this->format];
        $parse_new->format = $this->format;
        $parse_new->file = $parse_new->name;
        $parse_new->writeFile(Parse::getFormats()[$this->format], $text, $parse_new->name);
        $parse_new->save();
    }

}