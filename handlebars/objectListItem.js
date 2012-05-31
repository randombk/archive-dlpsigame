(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['objectListItem.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<div class=\"objectListItem blue-over\" data-objectID=\"";
  if (stack1 = helpers.objectID) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.objectID; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n	<table style=\"width: 100%;\" class=\"scaffold\">\r\n		<tr>\r\n			<td rowspan=\"5\" style=\"width: 60px;\">\r\n				<img src=\"http://placehold.it/60x60&text=";
  if (stack1 = helpers.objectID) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.objectID; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n			</td>\r\n			<td>";
  if (stack1 = helpers.objectName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.objectName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n		</tr>\r\n		<tr>\r\n			<td>(";
  if (stack1 = helpers.objectCoord) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.objectCoord; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ")</td>\r\n		</tr>\r\n		<tr>\r\n			<td>Weight: <span class=\"";
  if (stack1 = helpers.storageColor) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.storageColor; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">";
  if (stack1 = helpers.usedStorage) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.usedStorage; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " / ";
  if (stack1 = helpers.maxStorage) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.maxStorage; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</span></td>\r\n		</tr>\r\n		<tr>\r\n			<td>Fleets: ...</td>\r\n		</tr>\r\n	</table>\r\n</div>";
  return buffer;
  });
})();