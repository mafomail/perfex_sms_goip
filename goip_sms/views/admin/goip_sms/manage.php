<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">GoIP SMS Settings</h3>
            </div>
            <div class="panel-body">
                <?php echo form_open(admin_url('goip_sms')); ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <h4>GoIP Configuration</h4>
                        
                        <div class="form-group">
                            <label for="host">GoIP Host/IP Address</label>
                            <input type="text" class="form-control" name="host" id="host" 
                                   value="<?php echo isset($settings['host']) ? $settings['host'] : ''; ?>" 
                                   placeholder="192.168.1.100">
                        </div>
                        
                        <div class="form-group">
                            <label for="port">Port</label>
                            <input type="text" class="form-control" name="port" id="port" 
                                   value="<?php echo isset($settings['port']) ? $settings['port'] : '80'; ?>" 
                                   placeholder="80">
                        </div>
                        
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="username" 
                                   value="<?php echo isset($settings['username']) ? $settings['username'] : 'admin'; ?>" 
                                   placeholder="admin">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password" 
                                   value="<?php echo isset($settings['password']) ? $settings['password'] : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="enabled" value="1" 
                                           <?php echo (isset($settings['enabled']) && $settings['enabled'] == '1') ? 'checked' : ''; ?>>
                                    Enable GoIP SMS
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h4>Test SMS</h4>
                        <div class="form-group">
                            <label for="test_phone">Test Phone Number</label>
                            <input type="text" class="form-control" id="test_phone" placeholder="+421901234567">
                        </div>
                        
                        <div class="form-group">
                            <label for="test_message">Test Message</label>
                            <textarea class="form-control" id="test_message" rows="3" placeholder="Test SMS message">Test SMS from GoIP</textarea>
                        </div>
                        
                        <button type="button" class="btn btn-info" onclick="testSMS()">Send Test SMS</button>
                        <div id="test_result" style="margin-top: 10px;"></div>
                    </div>
                </div>
                
                <hr>
                
                <h4>Automatic SMS Triggers</h4>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="invoice_created_enabled" value="1" 
                                           <?php echo (isset($settings['invoice_created_enabled']) && $settings['invoice_created_enabled'] == '1') ? 'checked' : ''; ?>>
                                    Send SMS when invoice is created
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="invoice_created_template">Invoice Created Template</label>
                            <textarea class="form-control" name="invoice_created_template" rows="3"><?php echo isset($settings['invoice_created_template']) ? $settings['invoice_created_template'] : ''; ?></textarea>
                            <small class="text-muted">
                                Available variables: {client_name}, {invoice_number}, {invoice_total}, {invoice_duedate}
                            </small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="payment_recorded_enabled" value="1" 
                                           <?php echo (isset($settings['payment_recorded_enabled']) && $settings['payment_recorded_enabled'] == '1') ? 'checked' : ''; ?>>
                                    Send SMS when payment is recorded
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_recorded_template">Payment Recorded Template</label>
                            <textarea class="form-control" name="payment_recorded_template" rows="3"><?php echo isset($settings['payment_recorded_template']) ? $settings['payment_recorded_template'] : ''; ?></textarea>
                            <small class="text-muted">
                                Available variables: {client_name}, {invoice_number}, {payment_amount}, {payment_date}
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                    <a href="<?php echo admin_url('goip_sms/sms_log'); ?>" class="btn btn-default">View SMS Log</a>
                </div>
                
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
function testSMS() {
    var phone = document.getElementById('test_phone').value;
    var message = document.getElementById('test_message').value;
    
    if (!phone || !message) {
        alert('Please enter phone number and message');
        return;
    }
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo admin_url('goip_sms/test_sms'); ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            var result = JSON.parse(xhr.responseText);
            var resultDiv = document.getElementById('test_result');
            
            if (result.success) {
                resultDiv.innerHTML = '<div class="alert alert-success">' + result.message + '</div>';
            } else {
                resultDiv.innerHTML = '<div class="alert alert-danger">' + result.error + '</div>';
            }
        }
    };
    
    xhr.send('phone=' + encodeURIComponent(phone) + '&message=' + encodeURIComponent(message));
}
</script>