(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['hoverResource.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<th colspan=\"2\">";
  if (stack1 = helpers.resName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</th>\r\n					";
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<th colspan=\"1\">";
  if (stack1 = helpers.resName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</th>\r\n					";
  return buffer;
  }

function program5(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Type: ";
  if (stack1 = helpers.resType) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resType; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n						<td class=\"dataHolder\">Quantity: ";
  if (stack1 = helpers.quantity) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.quantity; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

function program7(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Type: ";
  if (stack1 = helpers.resType) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resType; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

function program9(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Weight: ";
  if (stack1 = helpers.resWeight) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resWeight; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n						<td class=\"dataHolder\">Total Weight: ";
  if (stack1 = helpers.resTotalWeight) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resTotalWeight; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

function program11(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Weight: ";
  if (stack1 = helpers.resWeight) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resWeight; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

function program13(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Value: ";
  if (stack1 = helpers.resValue) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resValue; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n						<td class=\"dataHolder\">Total Value: ";
  if (stack1 = helpers.resTotalValue) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resTotalValue; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

function program15(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n						<td class=\"dataHolder\">Value: ";
  if (stack1 = helpers.resValue) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resValue; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</td>\r\n					";
  return buffer;
  }

  buffer += "<div class=\"tooltipContent\">\r\n	<span class=\"tableHolder\">\r\n		<table class=\"hoverTable\">\r\n			<tbody>\r\n				<tr>\r\n					<th rowspan=\"4\" class=\"resImgHolder\"><img src=\"http://placehold.it/100x80/BBB&text=";
  if (stack1 = helpers.resImage) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resImage; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"></th>\r\n					";
  stack1 = helpers['if'].call(depth0, depth0.quantity, {hash:{},inverse:self.program(3, program3, data),fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\r\n				</tr>\r\n				<tr>\r\n					";
  stack1 = helpers['if'].call(depth0, depth0.quantity, {hash:{},inverse:self.program(7, program7, data),fn:self.program(5, program5, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\r\n				</tr>\r\n				<tr>\r\n					";
  stack1 = helpers['if'].call(depth0, depth0.quantity, {hash:{},inverse:self.program(11, program11, data),fn:self.program(9, program9, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\r\n				</tr>\r\n				<tr>\r\n					";
  stack1 = helpers['if'].call(depth0, depth0.quantity, {hash:{},inverse:self.program(15, program15, data),fn:self.program(13, program13, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\r\n				</tr>\r\n				<tr>\r\n					<td class=\"resDescHolder\" colspan=\"3\">";
  if (stack1 = helpers.resDesc) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.resDesc; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "</td>\r\n				</tr>\r\n			</tbody>\r\n		</table>\r\n	</span>\r\n</div>";
  return buffer;
  });
})();