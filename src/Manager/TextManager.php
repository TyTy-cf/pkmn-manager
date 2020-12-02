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
        // add a - before an uppercase letter
        $text = preg_replace('/(?<!\ )[A-Z]/', '-$0', $text);
        // check if the 1st letter become a - and remove it
        if (substr($text, 0, 1) === '-')
        {
            $text = substr($text, 1);
        }

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * @param string $className
     * @param string $text
     * @return string
     */
    public function generateSlugFromClass(string $className, string $text): string
    {
        $splitClass = explode('\\', $className);
        return $this->slugify($splitClass[sizeof($splitClass)-1]) . '-' . $text;
    }

    /**
     * @param string|null $text
     * @return string|string[]
     */
    public function removeReturnLineFromText(?string $text)
    {
        if ($text !== null)
        {
            return str_replace("\n", " ", $text);
        }
        return null;
    }

}