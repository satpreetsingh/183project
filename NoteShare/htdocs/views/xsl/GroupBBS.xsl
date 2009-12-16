<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:fb="http://www.facebook.com/2008/fbml" 
>

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<!--
  <SessionBBSThread>
    <SessionBBSPost Id="##" PostDate="" UserId="" SessionId="">
      Topic Header
    </SessionBBSPost\>
  </SessionBBSThread>
-->
<xsl:template match="StudyGroupBBSThread">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
  <xsl:variable name="sessionId"><xsl:value-of select="SessionId" /></xsl:variable>
  <xsl:variable name="parentId"><xsl:value-of select="ParentId" /></xsl:variable>
  <table>
    <tr>
      <td class="fbFont sessionBBSHeadingBar">
        <h1 class="subtitle">Topic: <xsl:value-of select="@Header" /></h1>
      </td>
    </tr>
  </table>
  <br />
  <table cellpadding="0" cellspacing="0" class="sessionBBSPost">
    <xsl:for-each select="StudyGroupBBSPost">
    <tr class="sessionBBSHeaderRow">
      <td class="sessionBBSUserPic" rowspan="2">
        <a target="_blank">
          <xsl:attribute name="href">
            http://www.facebook.com/profile.php?id=<xsl:value-of select="@UserId" />
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
            http://www.facebook.com/profile.php?id=<xsl:value-of select="@UserId" />
          </xsl:attribute>
          <xsl:value-of select="@UserName" />
        </a>
      </td>
      <td class="fbFont sessionBBSHeadingBar right normal">
        on <xsl:value-of select="@PostDate" />
      </td>
      <td rowspan="2" class="fbFont right" valign="top">
        <br />
        <xsl:if test="$viewUserId=@UserId">
          [
          <a target="_top" onclick="return confirm('Really delete this post?');">
            <xsl:attribute name="href">
              http://apps.facebook.com/notesharesep/controllers/GroupBBS.php?groupBBSDEL=1&amp;ns_session=<xsl:value-of select="$sessionId" />&amp;nsStudyGroup=<xsl:value-of select="@StudyGroupId" />&amp;parentId=<xsl:value-of select="$parentId" />&amp;postId=<xsl:value-of select="@Id" />
            </xsl:attribute>
            X
          </a>]
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
</xsl:stylesheet>
