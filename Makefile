
.PHONY: all pkg pkg-ng

all:

pkg:
	./make-package-debian
	ls -t gestex_*.deb | tail -n +5 | xargs -r rm -f

pkg-ng:
	./make-package-debian -n
	ls -t gestex-ng_*.deb | tail -n +10 | xargs -r rm -f
