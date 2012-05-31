(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['researchListItem.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression;


  buffer += "<div class=\"researchListItem ";
  if (stack1 = helpers.techColor) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.techColor; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "-over\" data-techID=\"";
  if (stack1 = helpers.techID) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.techID; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n	<table style=\"width: 100%;\" class=\"scaffold\">\r\n		<tr>\r\n			<td rowspan=\"5\" style=\"width: 61px;\">\r\n				<img width=\"60\" height=\"52\" src=\"resources/images/research/";
  if (stack1 = helpers.techImage) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.techImage; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n			</td>\r\n			<td>";
  if (stack1 = helpers.techName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.techName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n		</tr>\r\n		<tr>\r\n			<td>Level ";
  if (stack1 = helpers.techLevel) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.techLevel; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n		</tr>\r\n		<tr>\r\n			<td>Progress: ";
  if (stack1 = helpers.techPoints) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.techPoints; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "/";
  if (stack1 = helpers.techPointsReq) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.techPointsReq; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n		</tr>\r\n	</table>\r\n</div>";
  return buffer;
  });
})();