var baseHref = document.getElementsByTagName('base')[0].href;
var base = baseHref.replace(location.origin, '');
if (base[base.length - 1] !== '/') {
  base = base + '/';
}

route.base(base);
route('', function() { loadStaticTemplatePage('index', initAutoComplete); });
route('/articles', loadArticlesPage);
route('/articles/*', loadArticlePage);
route('/diseases', loadDiseasesPage);
route('/diseases/*', loadDiseasePage);
route('/documentation', function() { loadStaticTemplatePage('documentation', initAccordion); });
route('/statistics', loadStatisticsPage);
//route('*', function(value) { console.log('catch-all', value); });

route.start();
