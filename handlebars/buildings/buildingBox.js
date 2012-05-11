(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['buildingBox.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression, self=this, helperMissing=helpers.helperMissing;

function program1(depth0,data) {
  
  var buffer = "", stack1;
  buffer += " Level ";
  if (stack1 = helpers.curLevel) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.curLevel; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " ";
  return buffer;
  }

function program3(depth0,data) {
  
  var buffer = "", stack1, stack2, options;
  buffer += " \r\n						Resources Required for Upgrade:<br>\r\n						";
  options = {hash:{},inverse:self.noop,fn:self.program(4, program4, data),data:data};
  stack2 = ((stack1 = helpers.key_value || depth0.key_value),stack1 ? stack1.call(depth0, depth0.nextResReq, options) : helperMissing.call(depth0, "key_value", depth0.nextResReq, options));
  if(stack2 || stack2 === 0) { buffer += stack2; }
  buffer += "			\r\n					";
  return buffer;
  }
function program4(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n							<span class=\"resLink\" data-res=\"";
  if (stack1 = helpers.key) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.key; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-quantity=\"";
  if (stack1 = helpers.value) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.value; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"></span>\r\n						";
  return buffer;
  }

function program6(depth0,data) {
  
  var buffer = "", stack1;
  buffer += " \r\n						<div class=\"buildingUpgrade buttonDiv\" data-buildingID=\"";
  if (stack1 = helpers.buildID) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildID; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-buildingLevel=\"";
  if (stack1 = helpers.nextLevel) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.nextLevel; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n							Upgrade to Level ";
  if (stack1 = helpers.nextLevel) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.nextLevel; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\r\n							<br>\r\n							(";
  if (stack1 = helpers.upgradeTime) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.upgradeTime; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ")\r\n						</div>\r\n					";
  return buffer;
  }

function program8(depth0,data) {
  
  
  return "\r\n						<div class=\"buildingUpgrade\">\r\n							Reached max level!\r\n						</div>\r\n					";
  }

  buffer += "<div class=\"buildingHolder\">\r\n	<div class=\"buildingItem\">\r\n		<img class=\"buildingImage\" src=\"resources/images/building/";
  if (stack1 = helpers.buildImage) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildImage; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\"></img>\r\n		<div class=\"buildingData\">	\r\n			<div class=\"buildingName\">";
  stack1 = helpers['if'].call(depth0, depth0.curLevel, {hash:{},inverse:self.noop,fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  if (stack1 = helpers.buildName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</div>\r\n			<div class=\"buildingDesc\">";
  if (stack1 = helpers.buildDesc) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildDesc; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "</div>\r\n			<div class=\"buildingControls\">\r\n				<div class=\"buildingData\">\r\n					";
  stack1 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.noop,fn:self.program(3, program3, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\r\n				</div>\r\n				<div class=\"buildingButtons\">\r\n					";
  stack1 = helpers['if'].call(depth0, depth0.nextLevel, {hash:{},inverse:self.program(8, program8, data),fn:self.program(6, program6, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\r\n					<div class=\"buildingInfo buttonDiv\" data-buildingID=\"";
  if (stack1 = helpers.buildID) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildID; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-buildingType=\"";
  if (stack1 = helpers.buildType) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildType; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n						Base Info\r\n					</div>\r\n					<div class=\"buildingDestroy buttonDiv\" data-buildingID=\"";
  if (stack1 = helpers.buildID) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildID; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-buildingLevel=\"";
  if (stack1 = helpers.nextDestroyLevel) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.nextDestroyLevel; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n						X\r\n					</div>\r\n					<div class=\"buildingRecycle buttonDiv\" data-buildingID=\"";
  if (stack1 = helpers.buildID) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildID; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" data-buildingLevel=\"";
  if (stack1 = helpers.nextDestroyLevel) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.nextDestroyLevel; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n						&#x2672; \r\n					</div>\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n</div>";
  return buffer;
  });
})();