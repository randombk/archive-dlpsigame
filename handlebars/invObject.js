(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['invObject.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<div class=\"invObject type_item\">\r\n	<img src=\"http://placehold.it/100x80/BBB&text=";
  if (stack1 = helpers.resImage) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resImage; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n	<div class=\"invInfo\">\r\n		<div class=\"itemQuantity\">";
  if (stack1 = helpers.quantity) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.quantity; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</div>\r\n		<div class=\"itemName\">";
  if (stack1 = helpers.resName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</div>\r\n	</div>\r\n</div>";
  return buffer;
  });
})();