
.PHONY: all pkg pkg-ng

all:

pkg:
	./make-package-debian

pkg-ng:
	./make-package-debian -n
