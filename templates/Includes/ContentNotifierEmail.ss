<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>
      Content Notifier Email
    </title>
  </head>
  <body bgcolor="#FFFFFF" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; margin: 0; padding: 0;">
    
    <!-- HEADER -->
    <table class="head-wrap" bgcolor="#eee" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
      <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
        <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
        </td>
        <td class="header container" align="" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">
          
          <!-- /content -->
          <div class="content" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #fff; max-width: 600px; display: block; margin: 0 auto; padding: 0;">
            <table bgcolor="#eee" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
              <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
                <td style="color:#333;font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
                  <h2>
                    $Headline
                  </h2>
                </td>
                <td align="right" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
                  <h6 style="color: #666; font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; font-weight: 900; font-size: 14px; text-transform: uppercase; margin: 0; padding: 0;" class="collapse">
                    $Total items
                  </h6>
                </td>
                
              </tr>
            </table>
          </div>
          <!-- /content -->
          
        </td>
        <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
        </td>
      </tr>
    </table>
    <!-- /HEADER -->
    <!-- BODY -->
    <table class="body-wrap" bgcolor="" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
      <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
        <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
        </td>
        <td class="container" align="" bgcolor="#FFFFFF" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">
          
          
          <!-- content -->
          <% loop $GroupedItems %>
          <div class="content" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">
            <h3 style="font-family: 'HelveticaNeue-Light', 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 27px; margin: 0 0 15px; padding: 0;">
              $Category
            </h3>
            
            <table bgcolor="" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
              <% loop $Children %>
              <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
                <td valign="top" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 4px 0 0;">
                  
                  <% if $Event == 'CREATED' %>
                  <span class="label success" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif !important; font-weight: normal; text-align: center; text-decoration: none; line-height: 1; white-space: nowrap; display: inline-block; position: relative; font-size: 0.61111rem; color: #fff; background-color: #43AC6A; margin: 0 0 inherit; padding: 0.22222rem 0.44444rem 0.33333rem;">
                    CREATED
                  </span>
                  <% else_if $Event == 'UPDATED' %>
                  <span class="label" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif !important; font-weight: normal; text-align: center; text-decoration: none; line-height: 1; white-space: nowrap; display: inline-block; position: relative; font-size: 0.61111rem; color: #fff;background-color:#008CBA;; margin: 0 0 inherit; padding: 0.22222rem 0.44444rem 0.33333rem;">
                    UPDATED
                  </span>
                  <% end_if %>
                  
                </td>
                $Record.EmailSummary
              </tr>
              <% end_loop %>
            </table>
          </div>
          <% end_loop %>
          <!-- /content -->
          
          
        </td>
        <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
        </td>
      </tr>
    </table>
    <!-- /BODY -->
    <!-- FOOTER -->
    <table class="footer-wrap" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; width: 100%; clear: both !important; margin: 0; padding: 0;">
      <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
        <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
        </td>
        <td class="container" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">
          
          <!-- content -->
          <div class="content" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">
            <table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
              <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
                <td align="center" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
                  <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">
                    
                  </p>
                  <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">
                    <a class="btn" href="$Link" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: block !important; background-image: none !important; background-color: #995240; margin: 0 0 10px; padding: 10px 16px;">
                      View all notifications
                    </a>
                  </p>
                  
                </td>
              </tr>
            </table>
          </div>
          <!-- /content -->
          
        </td>
        <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
        </td>
      </tr>
    </table>
    <!-- /FOOTER -->
  </body>
</html>
