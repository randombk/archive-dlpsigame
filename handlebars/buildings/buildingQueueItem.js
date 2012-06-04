(function() {
  var template = Handlebars.template, templates = Handlebars.templates = Handlebars.templates || {};
templates['buildingQueueItem.tmpl'] = template(function (Handlebars,depth0,helpers,partials,data) {
  this.compilerInfo = [4,'>= 1.0.0'];
helpers = this.merge(helpers, Handlebars.helpers); data = data || {};
  var buffer = "", stack1, functionType="function", escapeExpression=this.escapeExpression, self=this;

function program1(depth0,data) {
  
  
  return "Current Construction:";
  }

function program3(depth0,data) {
  
  
  return "Queued Construction:";
  }

function program5(depth0,data) {
  
  var buffer = "", stack1;
  buffer += "\r\n		<span id=\"";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" class='mousePointer progressbar countdown' data-progressbar='yes' data-beginning='";
  if (stack1 = helpers.startTime) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.startTime; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "' data-end='";
  if (stack1 = helpers.endTime) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.endTime; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "' data-callback='";
  if (stack1 = helpers.callback) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.callback; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "'>\r\n			<span id=\"text-";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\" class=\"ui-progressbar-centerText\"></span>\r\n		</span>\r\n	";
  return buffer;
  }

  buffer += "<div class='buildingQueueItem'>\r\n	";
  stack1 = helpers['if'].call(depth0, depth0.endTime, {hash:{},inverse:self.program(3, program3, data),fn:self.program(1, program1, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += " ";
  if (stack1 = helpers.operation) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.operation; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " ";
  if (stack1 = helpers.buildName) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildName; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + " (Level ";
  if (stack1 = helpers.buildLevel) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.buildLevel; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + ")\r\n	";
  stack1 = helpers['if'].call(depth0, depth0.endTime, {hash:{},inverse:self.noop,fn:self.program(5, program5, data),data:data});
  if(stack1 || stack1 === 0) { buffer += stack1; }
  buffer += "\r\n	<div class=\"buildingQueueCancel buttonDiv\" data-id=\"";
  if (stack1 = helpers.id) { stack1 = stack1.call(depth0, {hash:{},data:data}); }
  else { stack1 = depth0.id; stack1 = typeof stack1 === functionType ? stack1.apply(depth0) : stack1; }
  buffer += escapeExpression(stack1)
    + "\">\r\n		Cancel\r\n	</div>\r\n</div>\r\n";
  return buffer;
  });
})();