using AppLink.core;
using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AppLink.api
{
    public static class test
    {
        public static DataTable atest(DBInstance dbmgr)
        {
            string sql = "select now()";
            return dbmgr.ExecuteDataTable(sql, null);
        }


        public static Object debugoauth(APIInstance api, List<Param> lst)
        {
            return api.CallApi("memberoauth/debugoauth", lst);
        }

        public static Object info(APIInstance api, List<Param> lst)
        {
            return api.CallApi("member/info", lst);
        }

        public static void atest(APIInstance api, List<Param> lst,APIInstance.CallbackDelegate callback)
        {
             api.CallApiAsync("blog/list", lst, callback);
        }
    }
}
