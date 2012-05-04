VERSION = $(shell grep 'Version:' readme.txt | sed 's/Version:[ \t]*//')

build:
	mkdir post-backgrounds/
	cp readme.txt post-backgrounds.php admin.php options.php post.php post-backgrounds/
	zip -r "post-backgrounds-${VERSION}.zip" post-backgrounds/
	rm -rf post-backgrounds/

.PHONY: build
