@echo off
for /R "C:\DLPWEB\DLPSIGAME\handlebars" %%f in (*.tmpl) do (
    handlebars %%~ff -f %%~df%%~pf%%~nf.js
)
exit