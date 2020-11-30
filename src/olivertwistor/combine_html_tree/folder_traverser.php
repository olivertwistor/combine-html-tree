<?php
namespace olivertwistor\combine_html_tree;

class folder_traverser
{
    private $found_files = array();

    public function __construct(string $base_folder, int $folder_depth = null)
    {
        $this->found_files = $this->scandir_recursive(
            $base_folder, $folder_depth
        );
    }

    private function scandir_recursive(string $folder, int $depth) : array
    {
        // Get the contents of $folder.
        $files = scandir($folder);
        if (is_null($files))
        {
            throw new \Exception('Failed to read contents of folder.');
        }

        $unwanted_files = array('.', '..');
        foreach ($files as $file)
        {
            // Skip files that match unwanted files.
            if (in_array($file, $unwanted_files))
            {
                continue;
            }

            // If the file is a subfolder (and the depth limit hasn't been
            // reached), go into that folder before doing anything else.
            if (is_dir($file))
            {
                if (is_null($depth))
                {
                    $this->scandir_recursive($file, $depth);
                }
                else if ($depth > 0)
                {
                    // Call this method recursively with ($depth - 1) to signal
                    // that we have gone one subfolder down the hierarchy.
                    $this->scandir_recursive($file, $depth - 1);
                }
            }
            else if (is_file($file))
            {
                $this->found_files[] = $file;
            }
        }
    }
}
