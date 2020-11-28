<?php


namespace App\Manager;


class TextManager
{
    /**
     * @param string $text
     * @return string
     */
    public function slugify(string $text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * @param string $remvovedValue
     * @param string $replace
     * @param string|null $text
     * @return string|string[]
     */
    public function removeCharacter(string $remvovedValue, string $replace, ?string $text)
    {
        if ($text !== null)
        {
            return str_replace($remvovedValue, $replace, $text);
        }
        return null;
    }

}