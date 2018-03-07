# UnusedFileRemover

PHP Command Line tool to analyze static HTML files in a public directory and list down and optionally delete image files used by `<img>` or `<source>` tags.

## Usage

    php run.php --imgdir="/path/to/images/dir" --pubdir="/path/to/public/dir/" --baseurl="https://site.base.url/" --pattern="/regex-to-match/" --also-pattern="/regex-to-match-for-additional-files/" --also-replace="/replacement-for-matches-for-additional-files/" --delete --list-unmatched
    
Example:

    php run.php --imgdir="./public/img/" --pubdir="./public/" --baseurl="https://inggo.xyz" --pattern="/\/public\/img\/thumbs\//" --also-pattern="/\/public\/img\/\//" --also-replace="/static/img/" --delete --list-unmatched
    
The above will go through the `/public/` directory and scan all `text/html` files for `<img>` or `<source>` tags. In addition, it will scan for inline styles and get images used as `background-images`.

It will then go through the `/public/img/` directory and cross-reference all image files inside. It will provide a list of all unused files within the html file.

Since `--pattern` is provided, it will only delete files matching the pattern, in this case, only those that are under the `/public/img/thumbs/` directory will be deleted. The CLI has an option to list down unused files that did not match the pattern.

Since `--also-pattern` and `--also-replace` are provided, in addition to the files in `--imgdir`, it will also do a RegEx-replace for `--also-pattern` and `--also-replace`. In this case, it will replace `/public/img/` to `/static/img/` in the image paths found and will also delete existing images in these directories.

Finally, the `--delete` and `--list-unmatched` flags will automatically delete the files and list out the unmatched files without prompting the user. If neither of this is provided, a command prompt will ask the user what he/she wants to do with the unused files found.
    
## Parameters

* `imgdir` - Required. Path to images directory.

* `pubdir` - Required. Path to the site's public directory.

* `baseurl` - Optional. Base URL of the site. This will be used to convert absolute URLs with the base URLs to their respective file paths.

* `pattern` - Optional. Only files that matches this RegEx pattern will be deleted. In addition, unmatched files will be provided as a separate list.

* `also-pattern` - Optional. If provided with `also-replace`, it will do a RegEx search-replace for files that matches the pattern and also delete these files.

* `also-replace` - Optional. See above.

* `delete` - Optional. If set, it will delete the matched files without asking for user confirmation / prompt.

* `list-unmatched` - Optional. If set, it will list unmatched files without asking for user confirmation / prompt.

If neither `delete` or `list-unmatched` flags are set, a user prompt will be shown to the user for additional options, including deleting of the matched files and listing of various files found.

### Dev Notes

Please note that I just made this tool very fast to search for unused images in [my blog][1] that uses [Hugo][2] so that I can automate my deployment process. Most of the tools I found were GUI-based and did not account for my needs (e.g., deletion of additonal files matching a pattern and accounting for `<source>` tags). I am aware there is a lot of room for improvement, but I will probably not be able to update this as often. Feel free to fork or do a PR.

[1]: https://eat.nyo.me/
[2]: http://gohugo.io/
