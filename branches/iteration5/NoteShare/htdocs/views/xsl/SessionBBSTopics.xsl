<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:fb="http://www.facebook.com/2008/fbml" 
>

<xsl:template match="/">
	<xsl:apply-templates/>
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
        <br />
        <xsl:if test="$viewUserId=@UserId">
          [
          <a target="_top">
            <xsl:attribute name="href">
              http://apps.facebook.com/notesharesep/controllers/SessionBBSTopics.php?ns_session=<xsl:value-of select="@SessionId" />&amp;postId=<xsl:value-of select="@Id" />&amp;funct=DELETEBBS
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

</xsl:stylesheet>
