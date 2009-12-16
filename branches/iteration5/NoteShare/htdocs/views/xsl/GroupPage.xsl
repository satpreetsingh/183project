<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:fb="http://www.facebook.com/2008/fbml" 
>

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="StudyGroupMetaDataResult">
  <table class="groupMetaData">
    <tr><td class="fbFont">Study Group:</td><td class="fbFont"><xsl:value-of select="." /></td></tr>
	  <tr><td class="fbFont">Description:</td><td class="fbFont"><xsl:value-of select="@Desc"/></td></tr>
  </table>
</xsl:template>


<xsl:template match="study_groupWallPosts">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
  <xsl:variable name="sessionId"><xsl:value-of select="SessionId" /></xsl:variable>
  <xsl:variable name="groupId"><xsl:value-of select="GroupId" /></xsl:variable>
  <table cellpadding="0" cellspacing="0" class="sessionBBSPost">
    <xsl:for-each select="post">
    <tr class="sessionBBSHeaderRow">
      <td class="sessionBBSUserPic" rowspan="2">
        <a target="_blank">
          <xsl:attribute name="href">
            http://www.facebook.com/profile.php?id=<xsl:value-of select="@user" />
          </xsl:attribute>
          <img class="profile_pic_linked">
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
      <td class="fbFont right">
        <xsl:if test="$viewUserId=@user">
          [
          <a target="_top">
            <xsl:attribute name="href">
              http://apps.facebook.com/notesharesep/controllers/GroupHomePage.php?ns_session=<xsl:value-of select="$sessionId" />&amp;nsStudyGroup=<xsl:value-of select="$groupId" />&amp;postId=<xsl:value-of select="@PostID" />&amp;funct=DELETEBBS
            </xsl:attribute>
            X
          </a>
          ]
        </xsl:if>
      </td>
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
<xsl:template match="study_groupWallPosts">
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

<xsl:template match="StudyGroupUserList">
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
		<xsl:for-each select="StudyGroupUserItem">
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


<xsl:template match="StudyGroupBBSTopics">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
  <xsl:variable name="sessionId"><xsl:value-of select="SessionId" /></xsl:variable>
  <table class="sessionBBS">
    <xsl:for-each select="StudyGroupBBSTopic">
    <tr>
      <td class="sessionBBSTopic fbFont">
        <a target="_top">
          <xsl:attribute name="href">
            http://apps.facebook.com/notesharesep/views/GroupBBS.php?ns_session=<xsl:value-of select="$sessionId" />&amp;parentId=<xsl:value-of select="@Id" />&amp;nsStudyGroup=<xsl:value-of select="@StudyGroupId" />
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
              http://apps.facebook.com/notesharesep/controllers/GroupHomePage.php?ns_session=<xsl:value-of select="$sessionId" />&amp;nsStudyGroup=<xsl:value-of select="@StudyGroupId" />&amp;postId=<xsl:value-of select="@Id" />&amp;funct=DELETEBBS
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

<xsl:template match="getStudyGroupNotes">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
  <xsl:variable name="sessionId"><xsl:value-of select="SessionId" /></xsl:variable>
  <xsl:variable name="studygroupId"><xsl:value-of select="StudyGroupId" /></xsl:variable>
  <table class="sessionNotes">
    <xsl:for-each select="getStudyGroupNote">
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
        <br />
        <xsl:if test="$viewUserId=@User_ID">
          [
          <a target="_top" onclick="return confirm('Really? Delete these notes?');">
            <xsl:attribute name="href">
              http://apps.facebook.com/notesharesep/controllers/GroupHomePage.php?ns_session=<xsl:value-of select="$sessionId" />&amp;nsStudyGroup=<xsl:value-of select="$studygroupId" />&amp;noteId=<xsl:value-of select="@Id" />&amp;funct=DELETENOTE
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
</xsl:stylesheet>

