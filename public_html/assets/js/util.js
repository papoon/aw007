function cleanTemplate(html)  {
  return html && html.replace(/\{\%.*\%\}/g,'');
}
