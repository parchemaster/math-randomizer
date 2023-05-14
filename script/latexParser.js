
//"y(t)=\left[ \frac{3}{2}-\frac{3}{2}e^{-\frac{2}{5}(t-4)}-\frac{3}{5}(t-4)e^{-\frac{2}{5}(t-4)} \right] \eta(t-4)"

function parseLatex(expr){
newExpr = expr.replace(/(.*[=])(.*)/g,'$2');
newExpr = newExpr.replace(/\s/g, '');
newExpr = newExpr.replace(/\\left/g, '');
newExpr = newExpr.replace(/\\right/g, '');
newExpr = newExpr.replace(/[[]/g, '(');
newExpr = newExpr.replace(/]/g, ')');
newExpr = newExpr.replace(/\\dfrac{(.+?)}{(.+?)}/g, '(($1)/($2))');
newExpr = newExpr.replace(/\\frac{(.+?)}{(.+?)}/g, '(($1)/($2))');
newExpr = newExpr.replace(/}/g, ')');
newExpr = newExpr.replace(/{/g, '(');
newExpr = newExpr.replace(/\\/g, '');
newExpr = newExpr.replace(/([)])([a-zA-Z0-9(])/gi, '$1*$2');
newExpr = newExpr.replace(/([a-zA-z0-9(])([(])/gi, '$1*$2');
newExpr = newExpr.replace(/([0-9])([a-zA-Z])/gi, '$1*$2');
newExpr = newExpr.replace(/([a-zA-Z])([0-9])/gi, '$1*$2');
newExpr = newExpr.replace(/([\^=(-])([*])/gi, '$1');
//newExpr = newExpr.replace(/\b[a-zA-Z]+\b/g,17.5);
newExpr = newExpr.replace(/\b(?!pi|squar|(\be\b)|sin|cos|lim|log|tan|int)[a-zA-Z]+\b/g,17.5);
newExpr = newExpr.replace(/["]/g,'');
return newExpr;
}
function isEqual(example,answer){

}