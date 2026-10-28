<style type="text/css">

/*------------------------------*\
	$SECTIONS
\*------------------------------*/

/* QUERY LOOP */

.md-query-loop + .md-query-loop { margin-block-start: var(--md-single-x); }
.md-query-loop-title { margin-block: 0 var(--md-half); }
.md-query-loop > .pagination { margin-block-start: var(--md-single); }
.md-query-loop > .prev-next .next { margin-inline-start: auto; }

/* AREAS */

.archive-sections:not(:last-child) { margin-block-end: var(--md-single-x); }

:not(.archive-sections) + .archive-sections:last-child { margin-block-start: var(--md-single-x); }

.sidebar .archive-sections { --md-columns-base: 1; }

.archive-sections-group > .box > .post-footer { padding-block-start: var(--md-half); }
