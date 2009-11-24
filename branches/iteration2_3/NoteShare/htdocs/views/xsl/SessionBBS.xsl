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
<xsl:template match="SessionBBSThread">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
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
    <xsl:for-each select="SessionBBSPost">
    <tr class="sessionBBSHeaderRow">
      <td class="sessionBBSUserPic" rowspan="2">
        <fb:profile-pic size="thumb">
          <xsl:attribute name="uid">
            <xsl:value-of select="@UserId" />
          </xsl:attribute>
        </fb:profile-pic>
      </td>
      <td class="fbFont sessionBBSHeadingBar">
        <fb:name linked="true" capitalized="true" useyou="false">
          <xsl:attribute name="uid">
            <xsl:value-of select="@UserId" />
          </xsl:attribute> 
        </fb:name>
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
              http://apps.facebook.com/notesharesep/controllers/SessionBBS.php?sessionBBSDEL=1&amp;ns_session=<xsl:value-of select="@SessionId" />&amp;post_id=<xsl:value-of select="@Id" />&amp;parentId=<xsl:value-of select="$parentId" />
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
