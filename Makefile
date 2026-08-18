PHPFILES:=$(wildcard *.php include/*.php module/*.php)

.PHONY: all schema pkg pkg-ng check

all:

schema: db-schema.svg db-schema.png

db-schema.dot: db-schema.sql
	@./sql2graphviz db-schema.sql > db-schema.dot

db-schema.svg: db-schema.dot
	@dot -Tsvg db-schema.dot > db-schema.svg

db-schema.png: db-schema.dot
	@dot -Tpng db-schema.dot > db-schema.png

pkg:
	./make-package-debian
	ls -t gestex_*.deb | tail -n +5 | xargs -r rm -f

pkg-ng:
	./make-package-debian -n
	ls -t gestex-ng_*.deb | tail -n +10 | xargs -r rm -f

check:
	@for f in $(PHPFILES); \
	do \
		out=$$(php -l $$f 2>&1) || { echo "$${out}"; exit 1; }; \
	done
