<?php

/**************
#�����ǳ��õĴ��룬��ע�����¼���
#1���벻Ҫ�ڱ�ҳ���г���html�Ĵ��룬�������Լ�������
#2����������һ��ҪoutputJSON���룬���������ҳ��jsonΪ�ա�
#3��һ����˵�������õ������ݿ������࣬����뿴��Ϥ���µĴ���
#4��һ�㶼����һ�����Ϊ׼���������Լ��Ĵ��������������ʹ��븴�ӡ���Ҫ��̫���֮�ж�������
#5��Ҫд��������߹��õ����ã������ϲ��common�ļ��л���datamgr�ļ�������Ӵ��룬������ļ�������


$dbmgr;//���ݿ��������
$sql="select now()";
$query=$dbmgr->query($sql);//�ύһ������
$result=$dbmgr->fetch_array($query);//����һ������
$result=$dbmgr->fetch_array_all($query);//���ض�������
$dbmgr->begin_trans();//�������ݿ�����
$dbmgr->commit_trans();//�ύ���ݿ�����
$dbmgr->rollback_trans();//�ع����ݿ�����һ�㲻д���Ҳ���ԣ�����SQL��������Զ��ع�

#�������õ����ݿⷽ��
$dbmgr->checkHave("����","where ����")��//����Ƿ����ĳһ������
$dbmgr->getNewId("����");//��ȡ�µ�ID���������id�ֶ�

#�������÷���
$str=parameter_filter($_str);//�����ݽ��й���
outputJson($array);//����תjson�����
outResult($code,$result,$return);//��׼�����������$code=��ʶ�룬$result=�������������$return=�������


logger_mgr::logError("������־");
logger_mgr::logInfo("������Ϣ��־");
logger_mgr::logDebug("Debug��־");


#�������ó���
USERROOT    //�û�Ŀ¼��Ŀ¼



д�������һ����¼Ӧ�ù����ڽӿ��е������XML������ܹ�ȥ���в���,�������API����Ч��
д�������һ����¼Ӧ�ù����ڽӿ��е������XML������ܹ�ȥ���в���,�������API����Ч��
д�������һ����¼Ӧ�ù����ڽӿ��е������XML������ܹ�ȥ���в���,�������API����Ч��
д�������һ����¼Ӧ�ù����ڽӿ��е������XML������ܹ�ȥ���в���,�������API����Ч��
д�������һ����¼Ӧ�ù����ڽӿ��е������XML������ܹ�ȥ���в���,�������API����Ч��
д�������һ����¼Ӧ�ù����ڽӿ��е������XML������ܹ�ȥ���в���,�������API����Ч��


����������
***************/

$myname=parameter_filter($_REQUEST["name"]);
$sql="select '$myname' name ";
$query=$dbmgr->query($sql);//�ύһ������
$result=$dbmgr->fetch_array($query);//����һ������

outputJson(outResult(0,"Success get name",$result["name"]));


?>