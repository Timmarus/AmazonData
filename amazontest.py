from mws import mws

access_key = '' #replace with your access key
merchant_id = '' #replace with your merchant id
secret_key = '' #replace with your secret key

reportid = '' #replace with report id

x = mws.Reports(access_key=access_key, secret_key=secret_key, account_id=merchant_id)
report = x.get_report(report_id=reportid)
response_data = report.original
print(response_data)
