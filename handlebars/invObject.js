(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['invObject.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<div class=\"invObject type_item\">\r\n	<img src=\"http://placehold.it/100x80/BBB&text=";
  if (stack1 = helpers.itemImage) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemImage; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n	<div class=\"invInfo\">\r\n		<div class=\"itemName\">\r\n			<p style=\"display: table-cell; vertical-align: middle; text-align: center; line-height: 95%;\">\r\n				";
  if (stack1 = helpers.itemName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.itemName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\r\n			</p>\r\n		</div>\r\n		<div class=\"itemQuantity\">";
  if (stack1 = helpers.quantity) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.quantity; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</div>\r\n	</div>\r\n</div>";
  return buffer;
  });
})();