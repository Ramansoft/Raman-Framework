#
# Zend Framework
#
# LICENSE
#
# This source file is subject to the new BSD license that is bundled
# with this package in the file LICENSE.txt.
# It is also available through the world-wide-web at this URL:
# http://framework.zend.com/license/new-bsd
# If you did not receive a copy of the license and are unable to
# obtain it through the world-wide-web, please send an email
# to license@zend.com so we can send you a copy immediately.
#
# @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
# @license    http://framework.zend.com/license/new-bsd     New BSD License
#

#
# Makefile - build Zend Framework product and documentation
#
# Main targets:
#  all - render DocBook manual in HTML.
#  clean - remove staged files.
#  check - validate DocBook manual using xmllint.
#  check1 - validate one DocBook XML file using xmllint.
#    Requires that you set the XMLFILE variable.
#  pdf-print - PDF version of manual, in two volumes; requires Apache FOP
#

# -- parameters you are likely to want to change --

# XMLFILE is the name of one DocBook XML file that you want to
#   test with xmllint, using the 'check1' target.

XSLTPROC=/usr/bin/xsltproc
XMLLINT=/usr/bin/xmllint
FOP=/usr/bin/fop
ECSRC=../trunk
HERE=/home/Ramansoft/Desktop/vendor/zendframework/zendframework1/documentation/manual/ar

DOCBOOK_DTD?=http://framework.zend.com/docbook/xml/4.5/docbookx.dtd
DOCBOOK_XSL?=http://framework.zend.com/docbook-xsl/htmlhelp/htmlhelp.xsl
DOCBOOK_FO_XSL?=http://framework.zend.com/docbook-xsl/fo/docbook.xsl
HTML_XSL=html.xsl
MANUAL_XML=manual.xml
MANUAL_PRINT1_XML=manual-print1.xml
MANUAL_PRINT2_XML=manual-print2.xml
MANUAL_LINT_XML=../en/manual-lint.xml.in
MODULE=module_specs
PDF_XSL=pdf-manual.xsl
XMLFILE=

all:	$(MANUAL_XML) html

# Build the docs in HTML format

html: html/index.html

html/index.html: $(MANUAL_XML) $(HTML_XSL)
	@echo "Rendering the whole manual with $(XSLTPROC)..."
	$(XMLLINT) --xinclude --output _temp_manual.xml $(MANUAL_XML)
	$(XSLTPROC) --xinclude --output html/index.html $(HTML_XSL) _temp_manual.xml
	@echo "Copying manual figures (recursively)..."
	-[ -d figures ] && cp -r figures html/

$(MANUAL_XML): ../en/$(MANUAL_XML).in
	sed -e 's!@DOCBOOK_DTD@!$(DOCBOOK_DTD)!' $< > $@
	
$(HTML_XSL): ../en/$(HTML_XSL).in
	sed -e 's!@DOCBOOK_XSL@!$(DOCBOOK_XSL)!' $< > $@

# Build the docs in PDF format

pdf-print: pdf-print-volume1 pdf-print-volume2

pdf-dir:
	@if [ ! -e pdf ]; then \
		mkdir pdf; \
	fi

pdf: pdf-dir $(MANUAL_XML) $(PDF_XSL)
	@echo "Rendering of the PDF manual with $(FOP)..."
	$(XMLLINT) --xinclude --output _temp_manual.xml $(MANUAL_XML)
	$(XSLTPROC) --xinclude --output ZendFramework.fo $(PDF_XSL) _temp_manual.xml
	"$(FOP)" -fo ZendFramework.fo -pdf pdf/ZendFramework.pdf

pdf-print-volume1: pdf-dir $(PDF_XSL) $(MANUAL_PRINT1_XML)
	@echo "Rendering volume 1 of the PDF manual with $(FOP)..."
	$(XMLLINT) --xinclude --output _temp_manual_1.xml $(MANUAL_PRINT1_XML)
	$(XSLTPROC) --xinclude --output ZendFramework.fo $(PDF_XSL) _temp_manual_1.xml
	"$(FOP)" -fo ZendFramework.fo -pdf pdf/ZendFrameworkVolume1.pdf

pdf-print-volume2: pdf-dir $(PDF_XSL) $(MANUAL_PRINT2_XML)
	@echo "Rendering volume 2 of the PDF manual with $(FOP)..."
	$(XMLLINT) --xinclude --output _temp_manual_2.xml $(MANUAL_PRINT1_XML)
	$(XSLTPROC) --xinclude --output ZendFramework.fo $(PDF_XSL) _temp_manual_2.xml
	"$(FOP)" -fo ZendFramework.fo -pdf pdf/ZendFrameworkVolume2.pdf

$(MANUAL_PRINT1_XML): ../en/$(MANUAL_PRINT1_XML).in
	sed -e 's!@DOCBOOK_DTD@!$(DOCBOOK_DTD)!' $< > $@
	
$(MANUAL_PRINT2_XML): ../en/$(MANUAL_PRINT2_XML).in
	sed -e 's!@DOCBOOK_DTD@!$(DOCBOOK_DTD)!' $< > $@

$(PDF_XSL): ../en/$(PDF_XSL).in
	sed -e 's!@DOCBOOK_FO_XSL@!$(DOCBOOK_FO_XSL)!' $< > $@

# Cleanup

check: $(MANUAL_XML)
	@echo "Checking the whole manual with $(XMLLINT)..."
	@$(XMLLINT) --xinclude --output _temp_manual.xml $(MANUAL_XML)
	@$(XMLLINT) --valid --noout --postvalid _temp_manual.xml \
	    && echo "OK"

check1: $(MANUAL_LINT_XML)
	@if [ -n "$(XMLFILE)" ] ; then \
	    if [ -f "$(MODULE)/$(XMLFILE)" ] ; then \
		echo "Checking $(XMLFILE) with $(XMLLINT)..." ; \
		sed -e 's!@XMLFILE@!$(MODULE)/$(XMLFILE)!' -e 's!@DOCBOOK_DTD@!$(DOCBOOK_DTD)!' $(MANUAL_LINT_XML) \
		| $(XMLLINT) --xinclude --output _temp_$(XMLFILE) - ; \
		$(XMLLINT) --valid --noout --postvalid _temp_$(XMLFILE) \
		  && echo "OK" ; \
	    else  \
		echo "File $(MODULE)/$(XMLFILE) not found." ; \
	    fi ; \
	else \
	    echo "Please specify a filename, e.g. 'make XMLFILE=filename.xml lint'" ; \
	fi

clean:
	-rm -f html/*.html html/HTML.manifest
	@if [ -f html/figures ] ; then \
		rm -Rf html/figures ; \
	fi
	-rm -f _temp_*.xml
	-rm -f /*.pdf
	-rm -f ZendFramework.fo

cleanall: clean
	-rm -f config.* configure
	-rm -rf autom4te.cache
	-rm -f Makefile
	-rm -f $(HTML_XSL) $(MANUAL_XML) $(MANUAL_PRINT1_XML) $(MANUAL_PRINT2_XML)
