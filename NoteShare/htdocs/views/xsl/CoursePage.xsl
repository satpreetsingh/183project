<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:fb="http://www.facebook.com/2008/fbml" 
>

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="SessionMetaData">
  <table class="sessionMetaData">
    <tr><td class="fbFont sessionName"><xsl:value-of select='.' /></td></tr>
	  <tr><td class="fbFont sessionDescr">Description:<p><xsl:value-of select="@Desc"/></p></td></tr>
  </table>
</xsl:template>

<xsl:template match="sessionWallPosts">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
	<table cellpadding="0" cellspacing="0" class="sessionBBSPost">
    <xsl:for-each select="post">
    <tr class="sessionBBSHeaderRow">
      <td class="sessionBBSUserPic" rowspan="2">
        <a target="_blank">
          <xsl:attribute name="href">
            http://www.facebook.com/profile.php?id=<xsl:value-of select="@user" />
          </xsl:attribute>
          <img class="profile_pic_linked" height="50" width="50">
            <xsl:attribute name="src">
              <xsl:choose>
                <xsl:when test="@PicURL!=''">
                  <xsl:value-of select="@PicURL" />
                </xsl:when>
                <xsl:otherwise>
                  http://static.ak.fbcdn.net/pics/q_silhouette.gif
                </xsl:otherwise>
              </xsl:choose> 
            </xsl:attribute>
            <xsl:attribute name="alt">
              <xsl:value-of select="@UserName" />
            </xsl:attribute>
            <xsl:attribute name="title">
              <xsl:value-of select="@UserName" />
            </xsl:attribute>
          </img>
        </a>
      </td>
      <td class="fbFont sessionBBSHeadingBar">  
        <a target="_blank" class="fbFont">
          <xsl:attribute name="href">
            http://www.facebook.com/profile.php?id=<xsl:value-of select="@user" />
          </xsl:attribute>
          <xsl:value-of select="@UserName" />
        </a>
      </td>
      <td class="fbFont sessionBBSHeadingBar right">
        <xsl:value-of select="@time" />
      </td>
<!--
      <td rowspan="2" class="fbFont right" valign="top">
        <br />
        <xsl:if test="$viewUserId=@user">
          [
          <a target="_top" onclick="return confirm('Really delete this post?');">
            <xsl:attribute name="href">
              http://apps.facebook.com/notesharesep/controllers/SessionBBS.php?sessionBBSDEL=1&amp;ns_session=<xsl:value-of select="@SessionId" />&amp;post_i$
            </xsl:attribute>
            X
          </a>]
        </xsl:if>
      </td>
-->
    </tr>
    <tr>
      <td class="fbFont sessionBBSPost" colspan="2">
        <xsl:value-of select="." />
      </td>
    </tr>
    <tr>
      <td><br /></td>
    </tr>
  </xsl:for-each>
  </table>
</xsl:template>

<!--
<xsl:template match="sessionWallPosts">
	<fb:serverfbml>
		<script type="text/fbml">
			<fb:wall>
				<xsl:for-each select="post">
					<fb:wallpost>
						<xsl:attribute name="uid"><xsl:value-of select="@user" /></xsl:attribute>
						<xsl:attribute name="t"><xsl:value-of select="@time" /></xsl:attribute>
						<xsl:value-of select="." />
					</fb:wallpost>
				</xsl:for-each>
			</fb:wall>
		</script>
	</fb:serverfbml>
</xsl:template>
-->
<xsl:template match="SessionUserList">
	<table class="padded">
    <tr>
      <td width="96"></td>
      <td width="96"></td>
      <td width="96"></td>
      <td width="96"></td>
      <td width="96"></td>
      <td width="96"></td>
      <td width="96"></td>
      <td width="96"></td>
    </tr>
    <tr>
		<xsl:for-each select="SessionUserItem">
			<xsl:sort select="@isFriend" order="descending" />
			<td>
				<xsl:attribute name="class">
					<xsl:choose>
						<xsl:when test="@isFriend=1">friend</xsl:when>
						<xsl:otherwise>notFriend</xsl:otherwise>
					</xsl:choose>
				</xsl:attribute>
        <div align="center">
				  <a target="_blank">
          <xsl:attribute name="href">
            http://www.facebook.com/profile.php?id=<xsl:value-of select="@user" />
          </xsl:attribute>
          <img class="profile_pic_linked" height="50" width="50">
            <xsl:attribute name="src">
              <xsl:choose>
                <xsl:when test="@PicURL!=''">
                  <xsl:value-of select="@PicURL" />
                </xsl:when>
                <xsl:otherwise>
                  http://static.ak.fbcdn.net/pics/q_silhouette.gif
                </xsl:otherwise>
              </xsl:choose>
            </xsl:attribute>
            <xsl:attribute name="alt">
              <xsl:value-of select="@UserName" />
            </xsl:attribute>
            <xsl:attribute name="title">
              <xsl:value-of select="@UserName" />
            </xsl:attribute>
          </img>
        </a>
        </div>
        <div align="center">
          <a target="_blank" class="fbFont">
            <xsl:attribute name="href">
              http://www.facebook.com/profile.php?id=<xsl:value-of select="@user" />
            </xsl:attribute>
            <xsl:value-of select="@UserName" />
          </a>
        </div>
			</td>
		</xsl:for-each> 
    </tr>
	</table>
</xsl:template>

<xsl:template match="SessionBBSTopics">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
  <table class="sessionBBS">
    <xsl:for-each select="SessionBBSTopic">
    <tr>
      <td class="sessionBBSTopic fbFont">
        <a target="_top">
          <xsl:attribute name="href">
            http://apps.facebook.com/notesharesep/views/SessionBBS.php?ns_session=<xsl:value-of select="@SessionId" />&amp;parentId=<xsl:value-of select="@Id" />
          </xsl:attribute>
          <xsl:value-of select="." />
        </a>
        <br />
        Created on <xsl:value-of select="@PostDate" />
      </td>
      <td class="fbFont">
        <xsl:if test="$viewUserId=@UserId">
          [
          <a target="_top">
            <xsl:attribute name="href">
              http://apps.facebook.com/notesharesep/controllers/CourseHomePage.php?ns_session=<xsl:value-of select="@SessionId" />&amp;parentId=<xsl:value-of select="@Id" />&amp;funct=DELETEBBS
            </xsl:attribute>
            X
          </a>
          ]
        </xsl:if>
      </td>
    </tr>
    </xsl:for-each>
  </table>
</xsl:template>

<!--
<getSessionNote>
  <getSessionNote User_ID="" Header="" Body="" Original_File_Name="" File_Size="1"></getSessionNote>
-->
<xsl:template match="getSessionNotes">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
  <table class="sessionNotes">
    <xsl:for-each select="getSessionNote">
    <tr>
      <td class="sessionNote fbFont">
        <a target="_top">
          <xsl:attribute name="href">
            <xsl:value-of select="." />
          </xsl:attribute>        
          <xsl:value-of select="@Header" />
        </a>
      </td>
      <td class="fbFont right">
        <xsl:if test="$viewUserId=@User_ID">
          [
          <a target="_top" onclick="return confirm('Really? Delete these notes?');">
            <xsl:attribute name="href">
              http://apps.facebook.com/notesharesep/controllers/CourseHomePage.php?ns_session=<xsl:value-of select="@SessionId" />&amp;noteId=<xsl:value-of select="@Id" />&amp;funct=DELETENOTE
            </xsl:attribute>
            X
          </a>
          ]
        </xsl:if>
      </td>
    </tr>
    <tr>
      <td class="sessionNote fbFont">
        <xsl:value-of select="@Body" />
      </td>
    </tr>
    </xsl:for-each>
  </table>
</xsl:template>


<xsl:template match="GroupList">
  <!-- Establish variable for the viewing user's facebook id -->
  <xsl:variable name="viewUserID"><xsl:value-of select="userID" /></xsl:variable>
  <xsl:variable name="viewSessionID"><xsl:value-of select="sessionID" /></xsl:variable>
 
  <table class="sessionStudyGroups">
    <xsl:for-each select="Group">
    <tr>
      <td class="two-thirds fbFont">
        <a target="_top">
          <xsl:attribute name="href">
            http://apps.facebook.com/notesharesep/views/GroupPage.php?ns_session=<xsl:value-of select="$viewSessionID" />&amp;nsStudyGroup=<xsl:value-of select="@Id" />
          </xsl:attribute>
          <xsl:value-of select="." />
        </a>
        <br />
        Description: <xsl:value-of select="@description" />
      </td>
      <td class="one-third right fbFont">
    	  <xsl:choose>
      		<xsl:when test="@member='True'">
    			[
    			  <a target="_top">
    		  		<xsl:attribute name="href">
    		  		  http://apps.facebook.com/notesharesep/controllers/GroupEnrollment.php?enroll=DROP&amp;ns_session=<xsl:value-of select="$viewSessionID" />&amp;nsStudyGroup=<xsl:value-of select="@Id" />
    		  		</xsl:attribute>
    		  		X
		    	  </a>
		    	]  
	    	  </xsl:when>
		      <xsl:when test="@member='False'">
		    	[
			      <a target="_top">
	      			<xsl:attribute name="href">
				        http://apps.facebook.com/notesharesep/controllers/GroupEnrollment.php?enroll=ADD&amp;ns_session=<xsl:value-of select="$viewSessionID" />&amp;nsStudyGroup=<xsl:value-of select="@Id" />
	      			</xsl:attribute>
			    	Join
			      </a>
		    	]  
      		</xsl:when>
		      <xsl:otherwise>
			      [Membership Status Unknown]
		      </xsl:otherwise>
	      </xsl:choose>
      </td>
    </tr>
    </xsl:for-each>
  </table>
</xsl:template>

</xsl:stylesheet>
